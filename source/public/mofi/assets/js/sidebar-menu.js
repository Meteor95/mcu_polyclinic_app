document.addEventListener('DOMContentLoaded', function() {
  const sidebarLinks = document.querySelectorAll('.sidebar-link.sidebar-title');
  
  sidebarLinks.forEach(link => {
    const submenu = link.nextElementSibling;
    if (submenu && submenu.classList.contains('sidebar-submenu')) {
      // Create and append arrow icon
      const arrowIcon = document.createElement('div');
      arrowIcon.className = 'according-menu';
      arrowIcon.innerHTML = '<i class="fa fa-angle-right"></i>';
      link.appendChild(arrowIcon);

      // Set initial styles for smooth transitions
      submenu.style.maxHeight = '0px';
      submenu.style.overflow = 'hidden';
      submenu.style.transition = 'max-height 0.3s ease-out';
      arrowIcon.querySelector('i').style.transition = 'transform 0.3s';
      
      // If the link or any of its children have the 'active' class, show the submenu
      if (link.classList.contains('active') || submenu.querySelector('.active')) {
        submenu.style.maxHeight = submenu.scrollHeight + 'px';
        arrowIcon.innerHTML = '<i class="fa fa-angle-down"></i>';
      }
      
      // Add click event listener
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Close all other submenus
        sidebarLinks.forEach(otherLink => {
          if (otherLink !== link) {
            const otherSubmenu = otherLink.nextElementSibling;
            const otherArrow = otherLink.querySelector('.according-menu i');
            if (otherSubmenu && otherSubmenu.classList.contains('sidebar-submenu')) {
              otherSubmenu.style.maxHeight = '0px';
              otherLink.classList.remove('active');
              if (otherArrow) {
                otherArrow.className = 'fa fa-angle-right';
              }
            }
          }
        });
        
        // Toggle the clicked submenu
        const arrow = arrowIcon.querySelector('i');
        if (submenu.style.maxHeight === '0px') {
          submenu.style.maxHeight = submenu.scrollHeight + 'px';
          arrow.className = 'fa fa-angle-down';
          link.classList.add('active');
        } else {
          submenu.style.maxHeight = '0px';
          arrow.className = 'fa fa-angle-right';
          link.classList.remove('active');
        }
      });
    }
  });
});