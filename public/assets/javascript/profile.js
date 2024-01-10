document.addEventListener('DOMContentLoaded', function () {
    var spanText = function (text) {
        var string = text.innerText;
        var spaned = '';
        for (var i = 0; i < string.length; i++) {
            if (string.substring(i, i + 1) === ' ') spaned += string.substring(i, i + 1);
            else spaned += '<span>' + string.substring(i, i + 1) + '</span>';
        }
        text.innerHTML = spaned;
    };

    var headline = document.querySelector(".h1");
    if (headline) {
        spanText(headline);

        let animations = document.querySelectorAll('h1 span');
        animations.forEach(function (letter, i) {
            letter.style.animationDelay = (i * 0.1);
        });
    }
});