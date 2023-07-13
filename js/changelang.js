/*======================== LANGUAGE SWITCHING MODE ========================*/

const select = document.querySelector('select');
const allLang = ['ru', 'en', 'es'];

select.addEventListener('change', changeURLLanguage);

// перенаправить на url с указанием языка
function changeURLLanguage(){
   let lang = select.value;
   location.href = window.location.pathname + '#' + lang;
   location.reload();
}

function changeLanguage(){
   let hash = window.location.hash;
   hash = hash.substring(1);
   console.log(hash);
   if (!allLang.includes(hash)) {
      location.href = window.location.pathname + '#ru';
      location.reload();
   }
   select.value = hash;
   for (let key in langArr) {
      let elem = document.querySelector('.lng-' + key)
      if (elem) {
         elem.innerHTML = langArr[key][hash];
      }
   }
}

changeLanguage();
