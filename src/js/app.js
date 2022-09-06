document.addEventListener("DOMContentLoaded", function() {
    eventsListeners()
    darkMode()
})

function darkMode() {
    //lee las preferencias del sistema
    const prefiereDarkMode = window.matchMedia("(prefers-color-scheme: dark)")

    //para que se aplique el cambio visual require de recargar la pagina
    if(prefiereDarkMode.matches) {  //devuelve true si está habilitado el modo oscuro
        document.body.classList.add("dark-mode")
    } else {
        document.body.classList.remove("dark-mode")
    }

    //se aplica el cambio visual automaticamente al cambiar las preferencias ya que esta escuchando el cambio todo el tiempo
    prefiereDarkMode.addEventListener("change", function() {
        if(prefiereDarkMode.matches) {  //devuelve true si está habilitado el modo oscuro
            document.body.classList.add("dark-mode")
        } else {
            document.body.classList.remove("dark-mode")
        }
    })

    const botonDarkMode = document.querySelector(".dark-mode-boton")
    botonDarkMode.addEventListener("click", function() {
        document.body.classList.toggle("dark-mode")
    })
}

function eventsListeners() {
    const mobileMenu = document.querySelector(".mobile-menu")
    mobileMenu.addEventListener("click", navegacionResponsive)
}

function navegacionResponsive() {
    const navegacion = document.querySelector(".navegacion")
    navegacion.classList.toggle("mostrar")
}

