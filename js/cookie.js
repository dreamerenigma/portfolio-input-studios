/*======================== COOKIE POLICY ========================*/
import '/scss/styles.scss'
import Cookies from 'js-cookie'

const COOKIE_NAME = 'visit'
const expires = new Date(new Date().getTime() + 2 * 60 * 1000)

function toHTML() {
   return `
      <div class="cookie-alert cookie-alert--js">
         <div class="cookie-alert__container">
            <div class="cookie-alert__left">
               <p class="mb-0">
                  Для коррекнтной работы сайта мы используем файлы Cookie. Это 
                  позволяет нам запомнить ваши настройки и предпочтения. <br />Для 
                  дальнейшего использования сайта вы должны прнять согласие на 
                  использование файлов Cookie.
               </p>
            </div>
            <div class="cookie-alert__right">
               <a href="https://inputstudios.ru/cookie_policy.html" target="_blank">
                  Подробнее
               </a>
               <button class="btn btn-success px-3 btn-sm ms-3 cookie-alert__btn--js">
                  Ок. Согласен!
               </button>
            </div>
         </div>
      </div>
   `
}

if (!Cookies.get(COOKIE_NAME)) {
   document.querySelector('body').insertAdjacentHTML('beforeend', toHTML())
   const cookieAlert = document.querySelector('.cookie-alert--js')
   const cookieBtn = document.querySelector('.cookie-alert__btn--js')

   setTimeout(() => cookieAlert.classList.add('is-show'), 1000)

   cookieBtn.addEventListener('click', setCookie)

   function setCookie() {
      cookieAlert.classList.remove('is-show')
      setTimeout(() => cookieAlert.remove(), 1000)
      Cookies.set(COOKIE_NAME, true, { expires })
   }
}
