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

    //Muestra campos condicionales en el formulario de contacto
    const metodoContacto = document.querySelectorAll('input[name="contacto[contacto]"]')   //selecciono todos los input que tengan como name = contacto[contacto]
    metodoContacto.forEach(input => input.addEventListener("click", mostrarMetodosContacto))    //si es querySelectorAll tengo que recorrer con foreach cada uno de sus elementos para poder hacer un addEventListener
}

function navegacionResponsive() {
    const navegacion = document.querySelector(".navegacion")
    navegacion.classList.toggle("mostrar")
}

function mostrarMetodosContacto(e) {
    const contactoDiv = document.querySelector("#contacto")
    if(e.target.value === "telefono") {
        contactoDiv.innerHTML = `
            <label for="telefono">Numero</label>
            <input type="tel" placeholder="Tu telefono" id="telefono" name="contacto[telefono]" required>

            <p>Elija la fecha y la hora para la llamada</p>
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="contacto[fecha]" required>

            <label for="hora">Hora</label>
            <input type="time" id="hora" min="09:00" max="18:00" name="contacto[hora]" required>
        `
    } else {
        contactoDiv.innerHTML = `
            <label for="email">Email</label>
            <input type="email" placeholder="Tu email" id="email" name="contacto[email]" required>
        `
    }
}
