import './bootstrap.js';
Turbo.session.drive = false;
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

document.addEventListener('DOMContentLoaded', function() {
    const tabForYou = document.getElementById('btn-for-you');
    const tabFollowing = document.getElementById('btn-following');
    const tabContents = {
        'for-you': document.getElementById('tweets-for-you'),
        'following': document.getElementById('tweets-following')
    };

    function switchTab(tab) {
        if(tab === 'for-you') {
            tabContents['for-you'].classList.remove('hidden');
            tabContents['for-you'].classList.add('block');
            tabContents['following'].classList.remove('block');
            tabContents['following'].classList.add('hidden');
            tabForYou.classList.add('text-white', 'font-bold', 'border-blue-500');
            tabForYou.classList.remove('text-[#888]', 'border-transparent');
            tabFollowing.classList.remove('text-white', 'font-bold', 'border-blue-500');
            tabFollowing.classList.add('text-[#888]', 'border-transparent');
        } else {
            tabContents['for-you'].classList.remove('block');
            tabContents['for-you'].classList.add('hidden');
            tabContents['following'].classList.remove('hidden');
            tabContents['following'].classList.add('block');
            tabFollowing.classList.add('text-white', 'font-bold', 'border-blue-500');
            tabFollowing.classList.remove('text-[#888]', 'border-transparent');
            tabForYou.classList.remove('text-white', 'font-bold', 'border-blue-500');
            tabForYou.classList.add('text-[#888]', 'border-transparent');
        }
    }
    tabForYou.addEventListener('click', () => switchTab('for-you'));
    tabFollowing.addEventListener('click', () => switchTab('following'));
    switchTab('for-you'); // mettre "For you" par défaut
});

window.showTab = function (tab, event) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.tab-button').forEach(el => {
    el.classList.remove('text-white', 'border-b-2', 'border-blue-500', 'font-semibold');
    el.classList.add('text-gray-400');
  });
  const content = document.getElementById('tab-' + tab);
  if (content) content.classList.remove('hidden');
  if (event && event.target) {
    event.target.classList.add('text-white', 'border-b-2', 'border-blue-500', 'font-semibold');
    event.target.classList.remove('text-gray-400');
  }
};

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById('editProfileModal');
  const openBtn = document.getElementById('editProfileBtn');

  const openModal = () => {
    if (modal) {
      modal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
    }
  }

  const closeModal = () => {
    if (modal) {
      modal.classList.add("hidden");
      modal.display.style = "none";
      document.body.style.overflow = "auto";
      modal.offsetHeight;
    }
  }

  if (openBtn) {
    openBtn.addEventListener("click", openModal);
  }

  if (modal) {
    modal.addEventListener("click", e => {
      if (e.target === modal) {
        closeModal();
      }
    })
  }

  document.addEventListener('keydown', e => {
    if (e.key === "Escape" && modal && !modal.classList.contains("hidden")) {
      closeModal();
    }
  })

})


document.addEventListener('DOMContentLoaded', function () {
  var buttons = document.querySelectorAll('.comment-toggle');
  if (!buttons.length) {
    console.log('Aucun bouton .comment-toggle trouvé');
    return;
  }
  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const targetSelector = btn.getAttribute('data-target');
      const target = document.querySelector(targetSelector);
      if (target) {
        target.classList.toggle('hidden');
        const svg = btn.querySelector('svg');
        if (svg) {
          svg.classList.toggle('rotate-180');
        }
      }
    });
  });
});


document.addEventListener('DOMContentLoaded', function () {
  const openBtn = document.getElementById('openTweetModalBtn');
  const modal = document.getElementById('tweetModal');
  const closeBtn = document.getElementById('closeModalBtn');

  if (openBtn && modal) {
    openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
  }
  if (closeBtn && modal) {
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
  }

  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.add('hidden');
    }
  });
});

// MENU BURGER

document.getElementById('openProfileMenu').addEventListener('click', function (e) {
  e.preventDefault();
  document.getElementById('profile-menu').classList.remove('-translate-x-full');
  document.getElementById('profile-menu-backdrop').classList.remove('hidden');
});
document.getElementById('closeProfileMenu').addEventListener('click', function () {
  document.getElementById('profile-menu').classList.add('-translate-x-full');
  document.getElementById('profile-menu-backdrop').classList.add('hidden');
});
document.getElementById('profile-menu-backdrop').addEventListener('click', function () {
  document.getElementById('profile-menu').classList.add('-translate-x-full');
  document.getElementById('profile-menu-backdrop').classList.add('hidden');
});

// AVATARS

document.querySelector('input[type=file][name$="[avatar]"]').addEventListener('change', function (e) {
  if (e.target.files && e.target.files[0]) {
    document.getElementById('avatarImg').src = URL.createObjectURL(e.target.files[0]);
  }
});

// BANNIERE

function previewBanner(event) {
    const input = event.target;
    const file = input.files && input.files[0];
    const img = document.getElementById('bannerImg');
    if (file && img) {
        img.src = URL.createObjectURL(file);
    }
}

document.getElementById('bannerInput')?.addEventListener('change', function(e) {
    const [file] = e.target.files
    if (file) {
        document.getElementById('bannerImg').src = URL.createObjectURL(file)
    }
});



