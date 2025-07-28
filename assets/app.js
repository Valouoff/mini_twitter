import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

document.getElementById('avatarImg').onclick = function() {
    document.querySelector('input[type=file][name$="[avatar]"]').click();
};

document.querySelector('input[type=file][name$="[avatar]"]').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        document.getElementById('avatarImg').src = URL.createObjectURL(e.target.files[0]);
    }
});

window.showTab = function(tab, event) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.tab-button').forEach(el => {
    el.classList.remove('text-blue-400', 'border-b-2', 'border-blue-500', 'font-semibold');
    el.classList.add('text-gray-400');
  });
  const content = document.getElementById('tab-' + tab);
  if(content) content.classList.remove('hidden');
  if(event && event.target) {
    event.target.classList.add('text-blue-400', 'border-b-2', 'border-blue-500', 'font-semibold');
    event.target.classList.remove('text-gray-400');
  }
};

document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('editProfileModal');
  const btn = document.getElementById('editProfileBtn');
  if (btn && modal) {
    btn.onclick = function() {
      modal.classList.remove('hidden');
    };
    function closeEditProfile() {
      modal.classList.add('hidden');
    }
    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeEditProfile();
    });
  }
});