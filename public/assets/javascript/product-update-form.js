/*A REFACTORISER*/
document.addEventListener('DOMContentLoaded', function () {
    const rgxSpec = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/;
    const rgxNum = /[0-9]/;
    const form = document.getElementById('product-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const productNameValue = document.getElementById('product-name').value.trim();
        const productNameMessage = document.getElementById('product-nameMessage');

        if (productNameValue.length < 2 || productNameValue.length > 50) {
            productNameMessage.innerHTML = '<small class="text-danger my-2">Le champs nom doit-être compris entre 2 et 50 caractères</small>';
        } else if (rgxNum.test(productNameValue)) {
            productNameMessage.innerHTML = '<small class="text-danger my-2">Le nom ne doit pas contenir de chiffres</small>';
        } else if (rgxSpec.test(productNameValue)) {
            productNameMessage.innerHTML = '<small class="text-danger my-2">Le nom ne doit pas contenir de caractères spéciaux</small>';
        } else {
            productNameMessage.innerHTML = '';
        }
       const productPriceValue = document.getElementById('product-price').value.trim();
        const productPriceMessage = document.getElementById('product-priceMessage');

        if (productPriceValue.length === 0) {
            productPriceMessage.innerHTML = '<small class="text-danger my-2">Le champs prix est requiq</small>';
        }else if (isNaN(productPriceValue)) {
            productPriceMessage.innerHTML = '<small class="text-danger my-2">Le prix doit être un nombre</small>';
        }else if (productPriceValue < 0) {
            productPriceMessage.innerHTML = '<small class="text-danger my-2">Le prix doit être positif</small>';
        }else if(rgxSpec.test(productPriceValue)){
            productPriceMessage.innerHTML = '<small class="text-danger my-2">Le prix ne doit pas contenir de caractères spéciaux</small>';
        }else{
            productPriceMessage.innerHTML = '';
        }
        const productDescriptionValue = document.getElementById('product-description').value.trim();
        const productDescriptionMessage = document.getElementById('product-descriptionMessage');

        if (productDescriptionValue.length < 20) {

            productDescriptionMessage.innerHTML = '<small class="text-danger my-2">Le champs description doit contenir au minimu 20 caractères</small>'; 
        }else {
            productDescriptionMessage.innerHTML = '';
        }
        const productIllustrationValue = document.getElementById('product-illustration').value.trim();
        const productIllustrationMessage = document.getElementById('product-illustrationMessage');
        const productCurrentIllustrationValue = document.getElementById('product-current-illustration').value.trim();
        if (productIllustrationValue.length === 0  && productCurrentIllustrationValue.length === 0) {
            productIllustrationMessage.innerHTML = '<small class="text-danger my-2">Le champs illustration est requis</small>';
        }else {
            productIllustrationMessage.innerHTML = '';
        }

        const productCategoryValue = document.getElementById('product-category').value.trim();
        const productCategoryMessage = document.getElementById('product-categoryMessage');

        if (productCategoryValue.length === 0) {
            productCategoryMessage.innerHTML = '<small class="text-danger my-2">Le champs catégorie est requis</small>';
        }else {
            productCategoryMessage.innerHTML = '';
        }

        const productStockValue = document.getElementById('product-stock').value.trim();
        const productStockMessage = document.getElementById('product-stockMessage');

        if (productStockValue.length === 0) {
            productStockMessage.innerHTML = '<small class="text-danger my-2">Le champs stock est requis</small>';
        }else if (isNaN(productStockValue)) {
            productStockMessage.innerHTML = '<small class="text-danger my-2">Le stock doit être un nombre</small>';
        }else if (productStockValue < 0) {
            productStockMessage.innerHTML = '<small class="text-danger my-2">Le stock doit être positif ou égal à 0</small>';
        }else if(rgxSpec.test(productStockValue)){
            productStockMessage.innerHTML = '<small class="text-danger my-2">Le stock ne doit pas contenir de caractères spéciaux</small>';
        }else{
            productStockMessage.innerHTML = '';
        }

        if (productNameMessage.innerHTML === '' && productPriceMessage.innerHTML === '' && productDescriptionMessage.innerHTML === '' && productIllustrationMessage.innerHTML === '' && productCategoryMessage.innerHTML === '' && productStockMessage.innerHTML === '') {
            form.submit();
        }

    });

});
