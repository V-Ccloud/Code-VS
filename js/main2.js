// this main js file

//---by default, get cart in session and add it to cart counter in index.php---
let theCart = sessionStorage.getItem('cart');
// if (theCart !== null) document.querySelector('.cart-counter').textContent = JSON.parse(theCart).length;
//console.log(theCart)
//---//by default, get cart in session and add it to cart counter in index.php---

// --function that handle cart--
function addToCart(productId, userId){
    userId = parseInt(userId);

    if (userId <= 0){ //user is not connected
        let cart=sessionStorage.getItem('cart');

        if(cart===null) cart = [];
        else cart = JSON.parse(cart);

        //--check if product already exists in cart or not----
        let found=false; //nous dit si on a trouvé le produit ds le panier
        for (i=0; i<cart.length; i++){
            if (productId == cart[i].id){ //exists
                found=true;
                cart[i].quantity = parseInt(cart[i].quantity) + 1;
                break;
            }
        }
        if (!found){ //not exist
            const toAdd = {
                id: productId,
                quantity: 1
            }
            cart.push(toAdd); //add the new product to cart
        }
        //--//check if product already exists in cart or not-----

        //----now save the cart in session//----
        document.querySelector('.cart-counter').textContent = cart.length; //update cart counter content in index.php
        cart = JSON.stringify(cart);
        sessionStorage.setItem('cart', cart);
        //saveCartInDataBase(); //save into data base if user is connected
        //----update cart product number on top right page//---

        myAlert("Ajouté au panier. <a href='?p=panier'?>Voir</a>");
    }
    else{ //user is connected
        var formData = {
            id: productId,
            action: "addToCart",
            quantity: 1
        };
        $.post("pages/php/action-on-cart.php", formData).done(function (data) {
            if (data.substring(0, 11) == "cartCounter"){
                document.querySelector('.cart-counter').textContent = parseInt(data.substring(12));
                myAlert("Ajouté au panier. <a href='?p=panier'?>Voir</a>");
            }
        });
    }
}
// --//function that handle cart--

// --function that displays alerts--
function myAlert(html){
    document.getElementById('myAlert').innerHTML = html;
    document.getElementById('myAlert').style.right = "0px";

    //----hide alert after some time//----
    let alertTimeout = setTimeout(() => {
        clearTimeout(alertTimeout);
        document.getElementById('myAlert').style.right = "-500px";
        document.getElementById('myAlert').innerHTML = "";
    }, 3000);
}
// --//function that displays alerts--


//----filter products------
function filterProducts(event, pageUrl){ //the url starting with ?
    if (event.target.value == "none"){
        window.location.href = "?q=produits";
    }
    else if (event.target.value == "recent"){
        window.location.href = pageUrl + "&filter=recent";
    }
    else if (event.target.value == "croissant"){
        window.location.href = pageUrl + "&filter=croissant";
    }
    else if (event.target.value == "decroissant"){
        window.location.href = pageUrl + "&filter=decroissant";
    }
    else if (event.target.value == "laitier"){
        window.location.href="?q=produits&type=laitier";
    }
    else if (event.target.value == "legume"){
        window.location.href="?q=produits&type=legume";
    }
    else if (event.target.value == "argume"){
        window.location.href="?q=produits&type=argume";
    }
}
//----//filter products------


// --sign up client--
function signUpClient(event){
    event.preventDefault();

    if (event.target.nom.value.length < 2 || event.target.nom.value.length > 20){
        myAlert("Nom invalide. 2 caractères min et 20 max");
    }
    else if (event.target.prenom.value.length < 2 || event.target.prenom.value.length > 20){
        myAlert("Prénom invalide. 2 caractères min et 20 max");
    }
    else if (event.target.mdp.value.length < 6){
        myAlert("Mot de passe invalide. 6 caractères min");
    }
    else{
        var formData = {
            nom: event.target.nom.value,
            prenom: event.target.prenom.value,
            email: event.target.email.value,
            mdp: event.target.mdp.value
        };
        $.post("pages/php/signUpClient.php", formData).done(function (data) {
            if (data.substring(0, 5) == "error") myAlert(data.substring(6));
            else{
                event.target.innerHTML = data;
                saveCartInDataBase(); //save user cart in data base (if he has cart in session)
                let x=setTimeout(() => {
                    sessionStorage.removeItem('cart'); //we remove cart from session because our cart is now in data base
                    clearTimeout(x);
                    window.location.href=""; //reload the page
                }, 3000);
            }
        });
    }
}
// --//sign up client--

// ----sign in client---
function signInClient(event){
    event.preventDefault();

    var formData = {
        email: event.target.email.value,
        mdp: event.target.mdp.value
    };
    $.post("pages/php/signInClient.php", formData).done(function (data) {
        if (data.substring(0, 5) == "error") myAlert(data.substring(6));
        else{
            event.target.innerHTML = data;
            saveCartInDataBase(); //save user cart in data base (if he has cart in session)
            let x=setTimeout(() => {
                sessionStorage.removeItem('cart'); //we remove cart from session because our cart is now in data base
                clearTimeout(x);
                window.location.href=""; //reload the page
            }, 3000);
        }
    });
}
// ----//sign in client---

//-----save user cart in data base (if he has cart in session)----
function saveCartInDataBase(){
    let userCart = sessionStorage.getItem('cart');
    if (userCart !== null){
        userCart = JSON.parse(userCart);
        for (j=0; j<userCart.length; j++){ //for each elt of cart, save it in data base
            var formData = {
                id: userCart[j].id,
                quantity: userCart[j].quantity,
                action: 'addToCart'
            };
            $.post("pages/php/action-on-cart.php", formData).done(function (data) {
                if (data.substring(0,11) == "cartCounter"){
                    document.querySelector('.cart-counter').textContent = data.substring(12); //update cart counter content in index.php
                }
            });
        }
    }
}
//-----//save user cart in data base (if he has cart in session)----


//-----animation logo on scroll------
$(document).ready(function(){
    $(window).scroll(function(){
        if ($(window).scrollTop() > 50 && $(window).scrollTop() <= 100){
            //---disable the text in the place it gonna live in//----
            $('.navbar-brand').css('opacity','0');

            //---move logo//----
            $('.logoToMoveOnScroll').css('height','32px');
            $('.logoToMoveOnScroll').css('top','0.5em');
        }
        else if ($(window).scrollTop() <= 50){
            //---move logo//----
            $('.logoToMoveOnScroll').css('height','22px');
            $('.logoToMoveOnScroll').css('top','0.8em');
            
            //---disable the text in the place it gonna live in//----
            $('.navbar-brand').css('opacity','1');
        }
    });
});
//-----//animation logo on scroll------