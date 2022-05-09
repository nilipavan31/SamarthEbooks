const readMoreBtn=document.querySelector('.read-more-btn');
const hello=document.querySelector('.hello');

readMoreBtn.addEventListener('click',(e)=>{
   hello.classList.toggle('show-more');
   if(readMoreBtn.innerText === 'Read More')
   {
	readMoreBtn.innerText = 'Read Less';
   }
   else
   {
	readMoreBtn.innerText = 'Read More';
  }
})