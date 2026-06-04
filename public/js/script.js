(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body when scrolling
   */
  function toggleScrolled() {
    const body = document.querySelector('body');
    const header = document.querySelector('#header');
    if (!header) return;
    window.scrollY > 100 ? body.classList.add('scrolled') : body.classList.remove('scrolled');
  }
  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileToggle = document.querySelector('.mobile-nav-toggle');
  const navMenu = document.querySelector('.navmenu');

  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', function() {
      navMenu.classList.toggle('navmenu-active');
      document.body.classList.toggle('mobile-nav-active');
      mobileToggle.classList.toggle('bi-list');
      mobileToggle.classList.toggle('bi-x');
    });
  }

  /**
   * Close mobile nav when clicking on links
   */
  document.querySelectorAll('#navmenu a').forEach(link => {
    link.addEventListener('click', function() {
      if (document.body.classList.contains('mobile-nav-active')) {
        navMenu.classList.remove('navmenu-active');
        document.body.classList.remove('mobile-nav-active');
        mobileToggle.classList.add('bi-list');
        mobileToggle.classList.remove('bi-x');
      }
    });
  });

  /**
   * Toggle dropdowns on mobile
   */
  document.querySelectorAll('.navmenu .dropdown > a').forEach(drop => {
    drop.addEventListener('click', function(e) {
      if (window.innerWidth <= 1199) { // Mobile & tablet
        e.preventDefault();
        const parent = this.parentElement;
        parent.classList.toggle('active');
        const submenu = parent.querySelector('ul');
        if (submenu) submenu.classList.toggle('show');
      }
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  const scrollTopBtn = document.querySelector('.scroll-top');
  if (scrollTopBtn) {
    function toggleScrollTop() {
      window.scrollY > 100 ? scrollTopBtn.classList.add('active') : scrollTopBtn.classList.remove('active');
    }
    scrollTopBtn.addEventListener('click', e => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    window.addEventListener('load', toggleScrollTop);
    document.addEventListener('scroll', toggleScrollTop);
  }

})();
