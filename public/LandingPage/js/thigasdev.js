$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);

    const src = urlParams.get('src');
    const utm = urlParams.get('utm');
    document.getElementById("src").value = src;
    document.getElementById("utm").value = utm;

});
document.addEventListener("DOMContentLoaded", function () {
    const memberLink = document.getElementById("memberAreaLink");

    if (memberLink) {
        memberLink.addEventListener("click", function (e) {
            e.preventDefault();

            const expires = new Date();
            expires.setTime(expires.getTime() + (1 * 60 * 60 * 1000));

            document.cookie = "Student=true; path=/; expires=" + expires.toUTCString() + ";";

            setTimeout(() => {
                window.location.href = this.href;
            }, 100);
        });
    }
});

function redirectCadastrar() {

    const src = document.getElementById("src").value;
    const utm = document.getElementById("utm").value;
    document.getElementById("utm").value = utm;
    if ((src != null && src != "") && (utm != null && utm != "")) {
        window.location.href = "/register?src=" + src + "&utm=" + utm + "";
    }
    else if ((src != null && src != "")) {
        window.location.href = "/register?src=" + src + "";
    }
    else if (utm != null && utm != "") {
        window.location.href = "/register?utm=" + utm + "";
    }

    else {
        window.location.href = "/register";
    }
}