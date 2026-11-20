window.onload = () => {

    const  BASEURL = "data/";
    function ajaxCallBack(nazivFajla, rezultat){
        $.ajax({
            url: BASEURL + nazivFajla,
            method: "get",
            dataType: "json",
            success: rezultat,
            error: function(jqXHR, exception){
                var msg = '';
            if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
            msg = 'Time out error.';
            } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
            } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            alert(msg);
            }
        })
    }

    var przd

    ajaxCallBack("navigation.json", function(rezultat){
        ispisNav(rezultat)
    }) 

    ajaxCallBack("products.json", function(rezultat){
        ispisProizvoda(rezultat);
        sacuvajLS("sviProizvodi",rezultat)
        przd = rezultat   
    })
     
    ajaxCallBack("sort.json", function(rezultat){
        sacuvajLS("sort",rezultat)
        kreirajPadajucuListu(rezultat, "ddlSort", "Sortiranje", "ispis-sort", "sort");

    })
    ajaxCallBack("publishers.json", function(rezultat){
        sacuvajLS("sviPublishers", rezultat)
        displayCategoriesAndPublisher("Publishers", rezultat, "#publishers")
    })
    ajaxCallBack("platforms.json", function(rezultat){
        sacuvajLS("svePlatforme",rezultat)
        displayCategoriesAndPublisher("Platforms", rezultat, "#platforms")  
    })
    ajaxCallBack("genere.json", function(rezultat){
        displayCategoriesAndPublisher("Generes", rezultat, "#categories")    
    })
     

    function ispisNav(values){
        let html = ""
        for(let value of values){
            html+=`<li><a href="${value.href}">${value.naziv}</a></li>`
        }
        $(".navigacija").html(html)
        $(".lblCartCount").html(dohvatiIzLS("productsCart").length)
    }
 
    $(document).on("change", "#ddlSort", promena);

    function promena(){
        let proizvodi = dohvatiIzLS("sviProizvodi");
        console.log(proizvodi)
     
         
        proizvodi = sortiranje(proizvodi);
     
        ispisProizvoda(proizvodi);
     }

    function kreirajPadajucuListu(niz, idListe, labela, idDiv, tip){
        let html = `<div class="form-group">
            <label class="form-label">${labela}</label>
            <select class="form-select" id="${idListe}">
                <option value="0">Choose...</option>`;
                for(let obj of niz){
                    if(tip == "sort"){
                        html += `<option value="${obj.vrednost}">${obj.naziv}</option>`
                    }
                    else{
                        html += `<option value="${obj.id}">${obj.naziv}</option>`
                    }
                }
            html += `</select>
        </div>`;
    
        document.querySelector(`#${idDiv}`).innerHTML = html;
        
    }

    function ispisProizvoda(niz){
        
        let html = "";
    
            for(let objProizvod of niz){
                
               
                html += `<div class="card m-3" style="width: 15rem;">
                <img src="${objProizvod.slika.src}" class="card-img-top pt-3" alt="${objProizvod.slika.alt}">
                <div class="card-body text-center">
                    <h5 class="card-title text-dark">${objProizvod.naziv}</h5>      
                    <p class="m-0">${obradaPublishers(objProizvod.proizvodjac, "sviPublishers")}</p>
                    <p>${obradaPublishers(objProizvod.platforme, "svePlatforme")} </p>
                    <p>${procesPrice(objProizvod.cenaInfo)} RSD</p>
                    <p>${objProizvod.newArrival?"Yes":"No"}</p>
                    <button class='btn btn-danger btn-cart-add' data-id='${objProizvod.id}'>Add to cart </button>
                    <div></div>
                    <div class="my-2"></div></div></div>`
            }
            
            $("#games").html(html);
        
    }

   

    function obradaPublishers(id,nazivLS){
        
        let html = "";
        let objPublisher = null;
        let niz = dohvatiIzLS(nazivLS);


        if(id != null){
            for(let obj of niz){
                
                if(obj.id == id){
                    objPublisher = obj;
                }
            }
            

            html += objPublisher.naziv
        }

        return html;
    }

    

    function sortiranje(nizProizvoda){
        let sortiraniProizvodi = [];
        let izbor = $("#ddlSort").val();
    
        if(izbor == "0"){
            sortiraniProizvodi = nizProizvoda;
        }
        else{
            sortiraniProizvodi = nizProizvoda.sort(function(a, b){
                if(izbor == "cena-asc"){
                    return (procesPrice(a.cenaInfo)) - (procesPrice(b.cenaInfo)) 
                }
                if(izbor == "cena-desc"){
                    return (procesPrice(b.cenaInfo)) - (procesPrice(a.cenaInfo))
                }
                if(izbor == "naziv-asc"){
                    if(a.naziv < b.naziv){
                        return -1;
                    }
                    else if(a.naziv > b.naziv){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
                if(izbor == "naziv-desc"){
                    if(a.naziv > b.naziv){
                        return -1;
                    }
                    else if(a.naziv < b.naziv){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }  
                if(izbor=="new-arrival"){
                    return b.newArrival - a.newArrival
                } 
            })
        }
        return sortiraniProizvodi;
    }

    function procesPrice(value){
        if(value.cenaNova == null) return value.cenaStara
        if(value.cenaStara == null) return value.cenaNova
    }

    function dohvatiIzLS(naziv){
        return JSON.parse(localStorage.getItem(naziv));
    }

    function sacuvajLS(naziv, vrednost){
        localStorage.setItem(naziv, JSON.stringify(vrednost));
    }
   


    function displayCategoriesAndPublisher(itemName, items, divElement) {
        let display = `<p class="m-0 text-light">${itemName}</p>`;
    
        items.forEach(element => {
            display += `<div class="form-check">
                            <input class="form-check-input ${itemName.toLowerCase()}" type="checkbox" value="${element.id}" id="${element.naziv}${element.id}">
                            <label class="form-check-label text-light" for="${element.naziv}${element.id}">
                            ${element.naziv}
                            </label>
                        </div>`
        })
    
        $(divElement).html(display)
        $(document).on('change', '.publishers, .platforms, .generes', function () {
            let products = przd
            products = filterProducts(products, ".generes", 'zanrovi')
            products = filterProducts(products, ".publishers", 'proizvodjac')
            products = filterProducts(products, ".platforms", "platforme")

            if(products.length == 0){
                $("#games").html(`<div class="alert alert-danger" role="alert">
                No such games!
              </div>`);
            }else{
                ispisProizvoda(products)
            }
            
            
            
        })
    }



    function filterProducts(products, divElement, propValue) {
        
        let criterium = $(divElement);
        console.log(criterium)
    
        let criteriumIDs = [];
    
        for (let i of criterium) {
            console.log(i)
            if (i.checked) {
                criteriumIDs.push($(i).val())
            }
        }

        
        let filterProducts = criteriumIDs.length != 0 ? products.filter(product => criteriumIDs.includes(String(product[propValue]))) : products;
        console.log(filterProducts)
        sacuvajLS("sviProizvodi",filterProducts)
  
        return filterProducts;
    }

    $(document).on("click", ".btn-cart-add", cartAdd)

    function cartAdd(){    
        let id = $(this).data('id')

        var cartProducts = dohvatiIzLS("productsCart")
        if(cartProducts){
            if(porductInCart(id)){
                updateQuanity(id)
            }else{
                addItem(id)
            }


        }else{
            CartFirst(id);
        }
        $(".lblCartCount").html(dohvatiIzLS("productsCart").length)

    }

    function CartFirst(id){
        let products = []
        products[0] = {
            id:id,
            quantity:1
        };
        sacuvajLS("productsCart", products)
    }

    function porductInCart(id){
        let cart = dohvatiIzLS("productsCart")
        return cart.filter(e => e.id == id).length
    }

    function updateQuanity(id){
        let cart = dohvatiIzLS("productsCart")

        for(let i in cart){
            if(cart[i].id==id){
                cart[i].quantity++
                break;
            }
        }
        sacuvajLS("productsCart",cart)
    }

    function addItem(id){
        let cart = dohvatiIzLS("productsCart")

        cart.push({
            id:id,
            quantity:1
        })

        sacuvajLS("productsCart",cart)
    }

    showData()

    function showData(){   
        let productsCart = dohvatiIzLS("productsCart");
        console.log(productsCart)
            
            if(productsCart == null){
                showEmptyCart();
            }
            else{
                printCart();
            }
    }
    function showEmptyCart(){
        $("#content").html(`<div class="alert alert-danger" role="alert">
        Your cart is empty!
      </div>`);
    }

    function printCart(){
        let allProducts = dohvatiIzLS("sviProizvodi");
        let productsCart = dohvatiIzLS("productsCart");

    let productsForDisplay = allProducts.filter(el => {
        for(let pCart of productsCart){
            if(el.id == pCart.id){
                el.quantity = pCart.quantity;
                return true;
            }
        }
        return false;
    })

    printTable(productsForDisplay);
    }

    function printTable(products){
        let html = `
        <div class="pb-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 p-5 bg-white rounded shadow-sm mb-5">
        <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th scope="col" class="border-0 bg-light">
                <div class="p-2 px-3 text-uppercase">Product</div>
              </th>
              <th scope="col" class="border-0 bg-light">
                <div class="py-2 text-uppercase">Unit Price</div>
              </th>
              <th scope="col" class="border-0 bg-light">
              <div class="py-2 text-uppercase">Total Price</div>
            </th>
              <th scope="col" class="border-0 bg-light">
                <div class="py-2 text-uppercase">Quantity</div>
              </th>
              <th scope="col" class="border-0 bg-light">
                <div class="py-2 text-uppercase">Remove</div>
              </th>
            </tr>
          </thead>
          <tbody>`;

        let price = 0
        for(let p of products){
            html += generateTr(p);

            price = price +((p.cenaInfo.cenaStara==null? p.cenaInfo.cenaNova: p.cenaInfo.cenaStara) * p.quantity)

        }
        
       
    
        html +=`</tbody>
        </table>
      </div>
      <div class="ads-grid py-sm-3 py-3">
		<div class="container mt-2">
			<div class="row">
				<div class="col-md-12 end p-10" id="cart-sum">

				</div>
			</div>
            <div class="end pt-3">
            <button type="button" class="btn clr" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Order
</button>
            </div>
		</div>
	</div>
    </div>
    </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      <form id="buyForm" action="logic/kupovina.php" method="post">
      <div class="mb-3">
        <label for="ime" class="form-label">Name</label>
        <input type="text" name="ime" class="form-control" id="ime">
        <span id="nameErr" class="nevidljiv text-danger">Wrong name, example: Pink</span>
      </div>

      <div class="mb-3">
        <label for="prezime" class="form-label">Last Name</label>
        <input type="text" name="prezime" class="form-control" id="prezime">
        <span id="lnameErr" class="nevidljiv text-danger">Wrong last name, example: Panter</span>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" id="email">
        <span id="emailErr" class="nevidljiv text-danger">Email erorr, example: veljko@gmail.com</span>
      </div>

      <div class="mb-3">
        <label for="category" class="form-label">Payment method</label>
        <select class="form-control" name="nacin_placanja" id="category">
            <option value="0">Choose..</option>
            <option value="1">Credit card</option>
            <option value="2">Cash</option>
        </select>
        <span id="categoryErr" class="nevidljiv text-danger">You must choose one method</span>
      </div>

      <input type="hidden" name="korpa" id="korpa">
      <input type="hidden" name="btnKupi" value="1">
    </form>
    
      </div>
      
      
      <div class="modal-footer justify-content-between">
      <div>
        <p class="text-success nevidljiv" id="ende">Successful shopping!</p>
        </div>
        <div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn clr" id="buy">Confirm</button>
        </div>

    
  </div>
</div>

      `;
    
        $("#content").html(html);
        $(".btn-remove").click(removeFromCart);   
        $("#cart-sum").html(`<div class="py-2 text-uppercase"><strong>Total price:  ${price} RSD</strong></div>`)
    }

    function generateTr(p){
        return  `<tr>
        <th scope="row" class="border-0">
          <div class="p-2">
            <img src="${p.slika.src}" alt="${p.slika.alt}" width="1" class="img-fluid rounded shadow-sm">
            <div class="ml-3 d-inline-block align-middle">
              <h5 class="mb-0"> <a href="#" class="text-dark d-inline-block align-middle">${p.naziv}</a></h5><span class="text-muted font-weight-normal font-italic d-block"> </span>
            </div>
          </div>
        </th>
        <td class="border-0 align-middle"><strong>${p.cenaInfo.cenaStara==null? p.cenaInfo.cenaNova: p.cenaInfo.cenaStara}</strong></td>
        <td class="border-0 align-middle"><strong>${(p.cenaInfo.cenaStara==null? p.cenaInfo.cenaNova: p.cenaInfo.cenaStara) * p.quantity}</strong></td>
        <td class="border-0 align-middle"><strong>${p.quantity}</strong></td>
        <td class="border-0 align-middle"><button class="btn-remove" data-id="${p.id}"><i class="fa fa-trash"></i></button></td>
      </tr>`
    }
    

    function removeFromCart(){ 
        let idP = $(this).data("id");

    
        let productsCart = dohvatiIzLS("productsCart");
        let filtered = productsCart.filter(el => el.id != idP);
    
        if(filtered.length == 0){
            localStorage.removeItem("productsCart");
            $(".lblCartCount").html("")
        }
        else{
            sacuvajLS("productsCart", filtered);
            $(".lblCartCount").html(dohvatiIzLS("productsCart").length)
        }
    
        showData();
    }

   



    var reName = /^[A-Zčćžšđ][a-zčćžšđ]{2,19}(\s[A-Zčćžšđ][a-zčćžšđ]{2,19})?$/;
    var reEmail= /^[a-z]((\.|-|)?[a-z0-9]){2,}@[a-z]((\.|-|)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i;

    $("#buy").on("click", function(){
        let brojGresaka = 0;
        
        if (!reName.test(ime.value)) {
            document.querySelector('#nameErr').classList.add("errorMess");
            document.querySelector('#nameErr').classList.remove("nevidljiv");
            brojGresaka++;
            ime.classList.add('prikazi');
        }
        else {
                document.querySelector('#nameErr').classList.remove("errorMess");
                document.querySelector('#nameErr').classList.add("nevidljiv");
                ime.classList.remove('prikazi');
        }
        if (!reName.test(prezime.value)) {
            document.querySelector('#lnameErr').classList.add("errorMess");
            document.querySelector('#lnameErr').classList.remove("nevidljiv");
            brojGresaka++;
            ime.classList.add('prikazi');
        }
        else {
                document.querySelector('#lnameErr').classList.remove("errorMess");
                document.querySelector('#lnameErr').classList.add("nevidljiv");
                ime.classList.remove('prikazi');
        }

        if(!reEmail.test(email.value)){
            document.querySelector("#emailErr").classList.add("errorMess");
            document.querySelector('#emailErr').classList.remove("nevidljiv");
            brojGresaka++;
            email.classList.add("prikazi");
        }
        else{
            document.querySelector("#emailErr").classList.remove("errorMess");
            document.querySelector('#emailErr').classList.add("nevidljiv");
            email.classList.remove("prikazi");
        }

        if($("#category").val() == 0){
            document.querySelector('#categoryErr').classList.add("errorMess");
            document.querySelector('#categoryErr').classList.remove("nevidljiv");
            brojGresaka++;
        }
        else{
            document.querySelector("#categoryErr").classList.remove("errorMess");
            document.querySelector('#categoryErr').classList.add("nevidljiv");
        }

        if(brojGresaka==0){
            document.querySelector("#ende").classList.remove("nevidljiv")
            document.querySelector("#korpa").value = JSON.stringify(dohvatiIzLS("productsCart") || []);
            document.querySelector("#buyForm").submit();
        }
        else{
            document.querySelector("#ende").classList.add("nevidljiv")
        }

    })

    

  
}