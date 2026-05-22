(function () {
  "use strict";
  if (localStorage.mamixrtl) {
    let html = document.querySelector("html");
    html.setAttribute("dir", "rtl");
    document
      .querySelector("#style")
      ?.setAttribute(
        "href",
        "../assets/libs/bootstrap/css/bootstrap.rtl.min.css"
      );
  }
  if (localStorage.mamixlayout) {
    let html = document.querySelector("html");
    html.setAttribute("data-nav-layout", "horizontal");
    document.querySelector("html").setAttribute("data-menu-styles", "light");
  }
  
  function localStorageBackup() {
    // if there is a value stored, update color picker and background color
    // Used to retrive the data from local storage
    if (localStorage.primaryRGB) {
      if (document.querySelector(".theme-container-primary")) {
        document.querySelector(".theme-container-primary").value =
          localStorage.primaryRGB;
      }
      document
        .querySelector("html")
        .style.setProperty("--primary-rgb", localStorage.primaryRGB);
    }
    if (localStorage.bodyBgRGB && localStorage.bodylightRGB) {
      let html = document.querySelector("html");
      html.setAttribute("data-theme-mode", "dark");
      html.setAttribute("data-menu-styles", "dark");
      html.setAttribute("data-header-styles", "dark");
    }
    if (localStorage.mamixdarktheme) {
      let html = document.querySelector("html");
      html.setAttribute("data-theme-mode", "dark");
    }
    let html = document.querySelector("html");
    if (window.innerWidth < 992) {
      html.setAttribute("data-toggled", "close");
    }

  }
  localStorageBackup();

})();
