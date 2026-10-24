// Form validation + loading indicator
const loader = document.getElementById('loader');

document.querySelectorAll('form').forEach(f => f.addEventListener('submit', ev => {
  let ok = true;

  f.querySelectorAll('[required]').forEach(i => {
    if (!i.value.trim()) { i.style.borderColor = '#dc2626'; ok = false; }
    else i.style.borderColor = '';
  });

  f.querySelectorAll('input[type=email]').forEach(i => {
    if (i.value && !/^\S+@\S+\.\S+$/.test(i.value)) { i.style.borderColor = '#dc2626'; ok = false; }
  });

  f.querySelectorAll('input[minlength]').forEach(i => {
    if (i.value.length < i.minLength) { i.style.borderColor = '#dc2626'; ok = false; }
  });

  if (!ok) { ev.preventDefault(); alert('Please fill in all fields correctly.'); }
  else loader.style.display = 'flex';
}));

window.addEventListener('pageshow', () => loader.style.display = 'none');