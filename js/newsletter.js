const modal = document.getElementById('newsletter-modal');
const close = document.querySelector('#newsletter-modal .close');

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('open-newsletter')) {
    modal.style.display = 'flex';
  }
});

close.onclick = () => modal.style.display = 'none';
window.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; };
