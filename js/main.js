document.addEventListener("DOMContentLoaded", () => {
  // Highlight the active navigation link based on the current page.
  const navLinks = document.querySelectorAll("nav ul li a");
  const currentPage = window.location.pathname.split("/").pop() || "index.html";
  navLinks.forEach(link => {
    if (link.getAttribute("href") === currentPage) {
      link.classList.add("active");
    }
  });

  // (Optional) Mobile menu toggle example:
  const menuToggle = document.querySelector('.menu-toggle');
  const navUl = document.querySelector('nav ul');
  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      navUl.classList.toggle('show');
    });
  }

  // Load and display testimonials with fade transitions
  const loadTestimonials = async () => {
    try {
      const response = await fetch('js/testimonials.json');
      const data = await response.json();
      const container = document.getElementById('testimonials-container');
      
      let currentIndex = 0;
      const showTestimonial = () => {
        container.classList.add('fade-out');
        setTimeout(() => {
          // Build new testimonial content wrapped in an active element
          const testimonial = data.testimonials[currentIndex];
          container.innerHTML = `
            <div class="testimonial active">
              <img src="/images/five-stars.png" alt="Five stars" class="testimonial-stars">
              <p class="testimonial-text">"${testimonial.text}"</p>
              <p class="testimonial-author">- ${testimonial.name}, ${testimonial.company}</p>
            </div>
          `;
          container.classList.remove('fade-out');
          container.classList.add('fade-in');
          setTimeout(() => container.classList.remove('fade-in'), 500);
          currentIndex = (currentIndex + 1) % data.testimonials.length;
        }, 500);
      };

      // Show first testimonial and then cycle
      showTestimonial();
      setInterval(showTestimonial, 5000);
    } catch (error) {
      console.error('Error loading testimonials:', error);
    }
  };

  loadTestimonials();
});
