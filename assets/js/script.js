'use strict';

// element toggle function
const elementToggleFunc = function (elem) { elem.classList.toggle("active"); }

// API functions
// API Configuration - work with both local and Replit
// const API_BASE_URL = window.location.origin;
const API_BASE_URL = window.location.origin + window.location.pathname;

console.log("API_BASE_URL: " + API_BASE_URL);
async function loadProfileData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=profile`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const responseText = await response.text();
    console.log('Raw response:', responseText);

    const profile = JSON.parse(responseText);
    console.log('Profile data received:', profile);

    if (profile && !profile.error) {
      const elements = {
        'profile-name': profile.name,
        'profile-title': profile.title,
        'profile-email': profile.email,
        'profile-phone': profile.phone,
        'profile-birthday': profile.birthday,
        'profile-location': profile.location,
        'about-text-content': profile.about_text,
        'navbar-name': profile.name || 'Portfolio'
      };

      Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
          element.textContent = value || 'Loading...';
        }
      });

      // Set href attributes
      const emailEl = document.getElementById('profile-email');
      const phoneEl = document.getElementById('profile-phone');
      if (emailEl && profile.email) emailEl.href = `mailto:${profile.email}`;
      if (phoneEl && profile.phone) phoneEl.href = `tel:${profile.phone}`;

      // Set avatar
      const avatarEl = document.getElementById('profile-avatar');
      if (avatarEl && profile.avatar) {
        avatarEl.src = `./assets/images/${profile.avatar}`;
      }
    } else {
      console.error('Profile data error:', profile.error || 'No data received');
    }
  } catch (error) {
    console.error('Error loading profile data:', error);
  }
}

async function loadServicesData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=services`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const services = await response.json();
    console.log('Services data received:', services);

    const servicesList = document.getElementById('services-list');

    if (servicesList && Array.isArray(services) && services.length > 0) {
      servicesList.innerHTML = '';
      services.forEach(service => {
        const serviceItem = document.createElement('li');
        serviceItem.className = 'service-item';

        serviceItem.innerHTML = `
          <div class="service-icon-box">
            <img src="./assets/images/${service.icon}" alt="${service.title} icon" width="40">
          </div>
          <div class="service-content-box">
            <h4 class="h4 service-item-title">${service.title}</h4>
            <p class="service-item-text">${service.description}</p>
          </div>
        `;
        servicesList.appendChild(serviceItem);
      });
    } else {
      console.error('Services data error:', services.error || 'No data received');
    }
  } catch (error) {
    console.error('Error loading services data:', error);
  }
}

async function loadProjectsData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=projects`);
    const projects = await response.json();
    console.log('Projects data received:', projects);

    if (projects && !projects.error) {
      const projectList = document.querySelector('.project-list');
      if (projectList) {
        projectList.innerHTML = projects.map(project => `
          <li class="project-item active" data-filter-item data-category="${project.category}">
            <a href="${project.link || '#'}" target="_blank">
              <figure class="project-img">
                <div class="project-item-icon-box">
                  <ion-icon name="eye-outline"></ion-icon>
                </div>
                <img src="./assets/images/${project.image}" alt="${project.title}" loading="lazy">
              </figure>
              <h3 class="project-title">${project.title}</h3>
              <p class="project-category">${project.category}</p>
            </a>
          </li>
        `).join('');

        // Initialize portfolio filter
        initPortfolioFilter();
      }
    }
  } catch (error) {
    console.error('Error loading projects:', error);
  }
}

function initPortfolioFilter() {
  const filterBtns = document.querySelectorAll('[data-filter-btn]');
  const filterItems = document.querySelectorAll('[data-filter-item]');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const selectedValue = this.innerText.toLowerCase();

      // Remove active class from all buttons
      filterBtns.forEach(filterBtn => filterBtn.classList.remove('active'));
      // Add active class to clicked button
      this.classList.add('active');

      filterItems.forEach(item => {
        const category = item.getAttribute('data-category').toLowerCase();

        if (selectedValue === 'all' || category === selectedValue) {
          item.classList.add('active');
          item.style.display = 'block';
        } else {
          item.classList.remove('active');
          item.style.display = 'none';
        }
      });
    });
  });
}

// Load and display blog data
async function loadBlogData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=blog`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const blogs = await response.json();
    console.log('Blog data received:', blogs);

    if (blogs && !blogs.error && Array.isArray(blogs)) {
      const blogList = document.getElementById('blog-posts-list');
      if (blogList) {
        if (blogs.length === 0) {
          blogList.innerHTML = '<li><p>No blog posts available.</p></li>';
        } else {
          blogList.innerHTML = blogs.map(blog => `
            <li class="blog-post-item">
              <a href="#">
                <figure class="blog-banner-box">
                  <img src="./assets/images/${blog.image}" alt="${blog.title}" loading="lazy">
                </figure>
                <div class="blog-content">
                  <div class="blog-meta">
                    <p class="blog-category">${blog.category}</p>
                    <span class="dot"></span>
                    <time datetime="${blog.created_at}">${new Date(blog.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</time>
                  </div>
                  <h3 class="h3 blog-item-title">${blog.title}</h3>
                  <p class="blog-text">${blog.content.substring(0, 100)}...</p>
                </div>
              </a>
            </li>
          `).join('');
        }
      }
    }
  } catch (error) {
    console.error('Error loading blog data:', error);
  }
}

// Load and display social media data
async function loadSocialData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=social`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const socials = await response.json();
    console.log('Social data received:', socials);

    if (socials && !socials.error && Array.isArray(socials)) {
      const socialList = document.querySelector('.contacts-list');
      if (socialList) {
        // Find existing social media items and update or add new ones
        const existingSocials = socialList.querySelectorAll('.contact-item[data-social="true"]');
        existingSocials.forEach(item => item.remove());

        socials.forEach(social => {
          const socialItem = document.createElement('li');
          socialItem.className = 'contact-item';
          socialItem.setAttribute('data-social', 'true');
          // Fix ion-icon names by removing 'ion-' prefix
          let iconName = social.icon.replace('ion-', '');
          socialItem.innerHTML = `
            <div class="icon-box">
              <ion-icon name="${iconName}"></ion-icon>
            </div>
            <div class="contact-info">
              <p class="contact-title">${social.platform}</p>
              <a href="${social.url}" class="contact-link">@${social.username}</a>
            </div>
          `;
          socialList.appendChild(socialItem);
        });
      }
    }
  } catch (error) {
    console.error('Error loading social data:', error);
  }
}

// Load and display testimonials data
async function loadTestimonialsData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=testimonials`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const testimonials = await response.json();
    console.log('Testimonials data received:', testimonials);

    if (testimonials && !testimonials.error && Array.isArray(testimonials)) {
      const testimonialsList = document.querySelector('.testimonials-list');
      if (testimonialsList) {
        if (testimonials.length === 0) {
          testimonialsList.innerHTML = '<li><p>No testimonials available.</p></li>';
        } else {
          testimonialsList.innerHTML = testimonials.map(testimonial => `
            <li class="testimonials-item">
              <div class="content-card" data-testimonials-item>
                <figure class="testimonials-avatar-box">
                  <img src="./assets/images/${testimonial.avatar}" alt="${testimonial.name}" width="60" data-testimonials-avatar>
                </figure>
                <h4 class="h4 testimonials-item-title" data-testimonials-title>${testimonial.name}</h4>
                <div class="testimonials-text" data-testimonials-text>
                  <p>${testimonial.content}</p>
                </div>
              </div>
            </li>
          `).join('');
        }
      }
    }
  } catch (error) {
    console.error('Error loading testimonials data:', error);
  }
}

// Load and display skills data
async function loadSkillsData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=skills`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const skills = await response.json();
    console.log('Skills data received:', skills);

    if (skills && !skills.error && Array.isArray(skills)) {
      const skillsList = document.querySelector('.skills-list');
      if (skillsList) {
        if (skills.length === 0) {
          skillsList.innerHTML = '<li><p>No skills data available.</p></li>';
        } else {
          skillsList.innerHTML = skills.map(skill => `
            <li class="skills-item">
              <div class="title-wrapper">
                <h5 class="h5">${skill.name}</h5>
                <data value="${skill.percentage}">${skill.percentage}%</data>
              </div>
              <div class="skill-progress-bg">
                <div class="skill-progress-fill" style="width: ${skill.percentage}%;"></div>
              </div>
            </li>
          `).join('');
        }
      }
    }
  } catch (error) {
    console.error('Error loading skills data:', error);
  }
}

// Load and display what I'm doing data
async function loadWhatDoingData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=what-doing`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const whatDoing = await response.json();
    console.log('What doing data received:', whatDoing);

    if (whatDoing && !whatDoing.error) {
      // Update the what I'm doing section title if it exists
      const whatDoingTitle = document.querySelector('.service-title');
      if (whatDoingTitle) {
        whatDoingTitle.textContent = whatDoing.title;
      }
    }
  } catch (error) {
    console.error('Error loading what doing data:', error);
  }
}

async function loadExperienceData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=experience`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const experiences = await response.json();
    console.log('Experience data received:', experiences);

    const educationList = document.getElementById('education-list');
    const experienceList = document.getElementById('experience-list');

    if (educationList && experienceList && Array.isArray(experiences)) {
      educationList.innerHTML = '';
      experienceList.innerHTML = '';

      experiences.forEach(exp => {
        const expItem = document.createElement('li');
        expItem.className = 'timeline-item';

        expItem.innerHTML = `
          <h4 class="h4 timeline-item-title">${exp.title}</h4>
          <span>${exp.period}</span>
          <p class="timeline-text">${exp.description}</p>
        `;

        if (exp.type === 'education') {
          educationList.appendChild(expItem);
        } else {
          experienceList.appendChild(expItem);
        }
      });
    }
  } catch (error) {
    console.error('Error loading experience data:', error);
  }
}

// Load and display map data
async function loadMapData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=map`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const mapData = await response.json();
    console.log('Map data received:', mapData);

    if (mapData && !mapData.error) {
      const mapbox = document.querySelector('.mapbox figure iframe');
      if (mapbox && mapData.embed_url) {
        mapbox.src = mapData.embed_url;
      }
    }
  } catch (error) {
    console.error('Error loading map data:', error);
  }
}

// Load and display clients data
async function loadClientsData() {
  try {
    const response = await fetch(`${API_BASE_URL}/api.php?action=clients`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const clients = await response.json();
    console.log('Clients data received:', clients);

    if (clients && !clients.error && Array.isArray(clients)) {
      const clientsList = document.querySelector('.clients-list');
      if (clientsList) {
        if (clients.length === 0) {
          clientsList.innerHTML = '<li><p>No clients data available.</p></li>';
        } else {
          clientsList.innerHTML = clients.map(client => `
            <li class="clients-item">
              <a href="${client.website || '#'}">
                <img src="./assets/images/${client.logo}" alt="${client.name}" loading="lazy">
              </a>
            </li>
          `).join('');
        }
      }
    }
  } catch (error) {
    console.error('Error loading clients data:', error);
  }
}

// Load all data when page loads
document.addEventListener('DOMContentLoaded', function() {
  loadProfileData();
  loadServicesData();
  loadProjectsData();
  loadBlogData();
  loadExperienceData();
  loadSocialData();
  loadTestimonialsData();
  loadSkillsData();
  loadWhatDoingData();
  loadMapData();
  loadClientsData();
});

// Floating navbar functionality
const floatingNavbar = document.getElementById('floating-navbar');
const floatingNavLinks = document.querySelectorAll('.floating-navbar .navbar-link');

// Add event listeners to floating navbar links
floatingNavLinks.forEach(link => {
  link.addEventListener('click', function() {
    const targetPage = this.textContent.toLowerCase();

    // Remove active class from all floating nav links
    floatingNavLinks.forEach(l => l.classList.remove('active'));
    // Add active class to clicked link
    this.classList.add('active');

    // Trigger page navigation
    navigateToPage(targetPage);
  });
});

function navigateToPage(pageName) {
  // Hide all pages
  const pages = document.querySelectorAll("[data-page]");
  const navLinks = document.querySelectorAll("[data-nav-link]");

  pages.forEach(page => page.classList.remove("active"));
  navLinks.forEach(link => link.classList.remove("active"));

  // Show target page
  const targetPage = document.querySelector(`[data-page="${pageName}"]`);
  if (targetPage) {
    targetPage.classList.add("active");
  }

  // Update bottom navbar if visible
  navLinks.forEach(link => {
    if (link.textContent.toLowerCase() === pageName) {
      link.classList.add("active");
    }
  });

  // Scroll to top
  window.scrollTo(0, 0);
}



// sidebar variables
const sidebar = document.querySelector("[data-sidebar]");
const sidebarBtn = document.querySelector("[data-sidebar-btn]");

// sidebar toggle functionality for mobile
sidebarBtn.addEventListener("click", function () { elementToggleFunc(sidebar); });



// testimonials variables
const testimonialsItem = document.querySelectorAll("[data-testimonials-item]");
const modalContainer = document.querySelector("[data-modal-container]");
const modalCloseBtn = document.querySelector("[data-modal-close-btn]");
const overlay = document.querySelector("[data-overlay]");

// modal variable
const modalImg = document.querySelector("[data-modal-img]");
const modalTitle = document.querySelector("[data-modal-title]");
const modalText = document.querySelector("[data-modal-text]");

// modal toggle function
const testimonialsModalFunc = function () {
  modalContainer.classList.toggle("active");
  overlay.classList.toggle("active");
}

// add click event to all modal items
for (let i = 0; i < testimonialsItem.length; i++) {

  testimonialsItem[i].addEventListener("click", function () {

    modalImg.src = this.querySelector("[data-testimonials-avatar]").src;
    modalImg.alt = this.querySelector("[data-testimonials-avatar]").alt;
    modalTitle.innerHTML = this.querySelector("[data-testimonials-title]").innerHTML;
    modalText.innerHTML = this.querySelector("[data-testimonials-text]").innerHTML;

    testimonialsModalFunc();

  });

}

// add click event to modal close button
modalCloseBtn.addEventListener("click", testimonialsModalFunc);
overlay.addEventListener("click", testimonialsModalFunc);



// custom select variables
const select = document.querySelector("[data-select]");
const selectItems = document.querySelectorAll("[data-select-item]");
const selectValue = document.querySelector("[data-selecct-value]");
const filterBtn = document.querySelectorAll("[data-filter-btn]");

select.addEventListener("click", function () { elementToggleFunc(this); });

// add event in all select items
for (let i = 0; i < selectItems.length; i++) {
  selectItems[i].addEventListener("click", function () {

    let selectedValue = this.innerText.toLowerCase();
    selectValue.innerText = this.innerText;
    elementToggleFunc(select);
    filterFunc(selectedValue);

  });
}

// filter variables
const filterItems = document.querySelectorAll("[data-filter-item]");

const filterFunc = function (selectedValue) {

  for (let i = 0; i < filterItems.length; i++) {

    if (selectedValue === "all") {
      filterItems[i].classList.add("active");
    } else if (selectedValue === filterItems[i].dataset.category) {
      filterItems[i].classList.add("active");
    } else {
      filterItems[i].classList.remove("active");
    }

  }

}

// add event in all filter button items for large screen
let lastClickedBtn = filterBtn[0];

for (let i = 0; i < filterBtn.length; i++) {

  filterBtn[i].addEventListener("click", function () {

    let selectedValue = this.innerText.toLowerCase();
    selectValue.innerText = this.innerText;
    filterFunc(selectedValue);

    lastClickedBtn.classList.remove("active");
    this.classList.add("active");
    lastClickedBtn = this;

  });

}



// contact form variables
const form = document.querySelector("[data-form]");
const formInputs = document.querySelectorAll("[data-form-input]");
const formBtn = document.querySelector("[data-form-btn]");

// add event to all form input field
for (let i = 0; i < formInputs.length; i++) {
  formInputs[i].addEventListener("input", function () {

    // check form validation
    if (form.checkValidity()) {
      formBtn.removeAttribute("disabled");
    } else {
      formBtn.setAttribute("disabled", "");
    }

  });
}

// Handle contact form submission
if (form) {
  form.addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(form);
    const data = {
      fullname: formData.get('fullname'),
      email: formData.get('email'),
      message: formData.get('message')
    };

    try {
      const response = await fetch(`${API_BASE_URL}/api.php?action=contact`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (result.success) {
        alert('Message sent successfully!');
        form.reset();
        formBtn.setAttribute("disabled", "");
      } else {
        alert('Error: ' + (result.error || 'Failed to send message'));
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Failed to send message. Please try again.');
    }
  });
}



// page navigation variables
const navigationLinks = document.querySelectorAll("[data-nav-link]");
const pages = document.querySelectorAll("[data-page]");

// add event to all nav link (bottom navbar)
for (let i = 0; i < navigationLinks.length; i++) {
  navigationLinks[i].addEventListener("click", function () {
    const targetPage = this.innerHTML.toLowerCase();

    // Update floating navbar
    floatingNavLinks.forEach(link => {
      link.classList.remove('active');
      if (link.textContent.toLowerCase() === targetPage) {
        link.classList.add('active');
      }
    });

    // Navigate to page
    navigateToPage(targetPage);
  });
}