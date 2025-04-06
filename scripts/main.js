(() => {
  "use strict";

  // =========================== Init AOS =========================== //
  AOS.init({
    once: true,
    offset: 50,
    disable: "tablet",
  });

  // =========================== Start of Header =========================== //
  // Header indicator position state
  const headerRef = document.querySelector(".header");
  const navRef = headerRef.querySelector(".navbar");
  const linkRef = navRef.querySelectorAll("a");
  const activeLinkRef = navRef.querySelector(".active");
  const navIndicatorRef = headerRef.querySelector(".indicator");
  const hideOnScrollRef = headerRef.querySelectorAll(".hideOnScroll");
  let indicatorPosition = null;

  const navTogglerRef = headerRef.querySelector(".navToggler");

  // Function to set indicator position
  const setIndicatorPosition = (left, width) => {
    indicatorPosition = { left, width };
    navIndicatorRef.style.left = `${indicatorPosition.left}px`;
    navIndicatorRef.style.width = `${indicatorPosition.width}px`;
  };

  // Update indicator position when active link changes
  window.addEventListener("load", () => {
    if (activeLinkRef) {
      setIndicatorPosition(activeLinkRef.offsetLeft, activeLinkRef.offsetWidth);
    }
    setTimeout(() => {
      navIndicatorRef.style.opacity = 1;
      navIndicatorRef.style.transform = "scaleX(1)";
    }, 300);
  });

  // Handle mouse leave event
  const handleLinkMouseLeave = () => {
    if (activeLinkRef) {
      setIndicatorPosition(activeLinkRef.offsetLeft, activeLinkRef.offsetWidth);
    }
  };
  navRef.addEventListener("mouseleave", handleLinkMouseLeave);

  // Handle mouse enter event
  const handleLinkMouseEnter = (event) => {
    const link = event.currentTarget;
    setIndicatorPosition(link.offsetLeft, link.offsetWidth);
  };

  // Handle link click event
  const handleLinkClick = (event) => {
    const link = event.currentTarget;
    activeLinkRef = link;
    setIndicatorPosition(link.offsetLeft, link.offsetWidth);
  };

  linkRef.forEach((link) => {
    link.addEventListener("mouseenter", handleLinkMouseEnter);
    link.addEventListener("click", handleLinkClick);
  });

  // Update Header element position on Scroll
  hideOnScrollRef.forEach((element) => {
    const handleScroll = () => {
      window.scrollY > 50
        ? element.classList.add("scrolled")
        : element.classList.remove("scrolled");
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  });

  // Open-Close Mobile Nav state
  let mobileNavOpen = false;
  const html = document.documentElement;
  const toggleMobileNav = () => {
    mobileNavOpen = !mobileNavOpen;
    if (mobileNavOpen) {
      html.classList.add("overflow-hidden");
      headerRef.classList.add("navOpen");
    } else {
      html.classList.remove("overflow-hidden");
      headerRef.classList.remove("navOpen");
    }
  };
  navTogglerRef.addEventListener("click", toggleMobileNav);

  // Change Header background color on scroll
  window.addEventListener("load", () => {
    const banner = document.querySelector(".banner");
    const bannerScrollHeight = banner ? banner.scrollHeight + 100 : 0;
    const observer = new IntersectionObserver(
      (entry) => {
        window.addEventListener("scroll", () =>
          entry[0].isIntersecting
            ? headerRef.classList.remove("active")
            : headerRef.classList.add("active")
        );

        // Hide Header on scroll down and show on scroll up
        let lastScrollTop = 0;
        const handleScroll = () => {
          const currentScrollTop = document.documentElement.scrollTop;

          currentScrollTop > bannerScrollHeight &&
          currentScrollTop > lastScrollTop
            ? headerRef.classList.add("invisible")
            : headerRef.classList.remove("invisible");

          lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop;
        };
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
      },
      { threshold: [0] }
    );
    if (banner !== null) {
      observer.observe(banner);
    }
  });
  // =========================== End of Header =========================== //

  // =========================== Start of Banner =========================== //
  const handleBannerScroll = () => {
    const bannerTextRef = document.querySelector(".banner .bg-text");
    if (bannerTextRef !== null) {
      const scrollValue = window.scrollY;
      bannerTextRef.style.opacity = (1000 - scrollValue) / 1000;
      bannerTextRef.style.transform = `translateX(-${scrollValue}px)`;
    }
  };
  window.addEventListener("scroll", handleBannerScroll);
  // =========================== End of Banner =========================== //

  // =========================== Start of About Image =========================== //
  window.addEventListener("load", () => {
    const aboutImagesRef = document.querySelector(".about-images");
    if (aboutImagesRef !== null) {
      const allImage = aboutImagesRef.querySelectorAll("img");
      const imageLength = allImage.length;
      const swapImageBtn = document.querySelector(".swap-images-btn");
      const swapImageBtnIcon = swapImageBtn.querySelector("svg");

      // generate random numbers
      const numbers = [];
      const min = -6;
      const max = 6;
      while (numbers.length < imageLength) {
        const randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
        if (!numbers.includes(randomNumber)) {
          numbers.push(randomNumber);
        }
      }

      // rotate images randomly and set zindex
      setTimeout(() => {
        aboutImagesRef.querySelectorAll("img").forEach((image, index) => {
          image.parentElement.style.transform = `rotate(${numbers[index]}deg)`;
          image.parentElement.style.zIndex = `${numbers[index]}`;
        });
      }, 300);

      // chnage zIndex on click to show image 1 by 1 and rotate button icon
      let currentZIndex = 1;
      let rotateValue = 0;
      const handleClick = () => {
        for (let i = 0; i < imageLength; i++) {
          allImage[i].parentElement.style.zIndex = currentZIndex;
          currentZIndex--;
          if (currentZIndex < -imageLength) {
            currentZIndex = 0;
          }
        }
        swapImageBtnIcon.style.transform = `rotate(${rotateValue + 360}deg)`;
        rotateValue += 360;
      };
      aboutImagesRef.addEventListener("click", handleClick);
      swapImageBtn.addEventListener("click", handleClick);
    }
  });
  // =========================== End of About Image =========================== //

  // =========================== Start of FAQ =========================== //
  const faqItemRef = document.querySelectorAll(".faq-item");
  if (faqItemRef.length !== 0) {
    faqItemRef.forEach((item) => {
      const faqItemHeaderRef = item.querySelector(".faq-item-header");
      const faqItemContentRef = item.querySelector(".faq-item-content");
      const faqItemIconPlus = item.querySelector("svg.inline-block");
      const faqItemIconMinus = item.querySelector("svg.hidden");

      const handleFaqItemClick = () => {
        faqItemContentRef.classList.toggle("hidden");
        faqItemIconPlus.classList.toggle("hidden");
        faqItemIconMinus.classList.toggle("hidden");
      };
      faqItemHeaderRef.addEventListener("click", handleFaqItemClick);
    });
  }
  // =========================== End of FAQ =========================== //

  // =========================== Start of Contact form =========================== //
  const contactFormRef = document.querySelector(".contact-form");
  if (contactFormRef !== null) {
    const submitBtnRef = contactFormRef.querySelector("button[type='submit']");
    const submitBtnTextRef = submitBtnRef.innerHTML;
    const statusRef = contactFormRef.querySelector(".status");
    const emailAddress = "aadarshkavita@gmail.com";
    const formsubmitURL = `https://formsubmit.co/ajax/${emailAddress}`;

    const formHandler = (e) => {
      e.preventDefault();

      submitBtnRef.innerHTML = "<span>Sending..</span>";

      fetch(formsubmitURL, {
        method: "POST",
        headers: { "Content-type": "application/json" },
        body: JSON.stringify({
          _subject: "Message form Gauarva Yadav's Personal Website!",
          name: full_name.value,
          email: email.value,
          message: message.value,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          statusRef.classList.remove("hidden");
          statusRef.style.color = "#16A34A";
          statusRef.innerHTML = "Submitted Successfully!";

          setTimeout(() => {
            statusRef.classList.add("hidden");
            statusRef.innerHTML = submitBtnTextRef;
          }, 5000);

          e.target.reset();
        })
        .catch((error) => {
          statusRef.classList.remove("hidden");
          statusRef.style.color = "#DC2626";
          statusRef.innerHTML = "Something went wrong!";
        });
    };
    contactFormRef.addEventListener("submit", formHandler);
  }

  // =========================== End of Contact form =========================== //

  // =========================== Start of Video Slider =========================== //
  window.addEventListener("load", () => {
    const videoSlider = document.querySelector(".video-slider");
    if (videoSlider !== null) {
      const sliderTrack = videoSlider.querySelector(".video-slider-track");
      const slides = videoSlider.querySelectorAll(".video-slide");
      const prevBtn = videoSlider.querySelector(".video-prev");
      const nextBtn = videoSlider.querySelector(".video-next");
      const videoThumbs = videoSlider.querySelectorAll(".video-thumb");
      const slideIndicators = videoSlider.querySelectorAll(".slide-indicator");
      
      // Each slide now represents a full slide with 3 videos
      let currentIndex = 0;
      const totalSlides = slides.length;
      
      // Update slide position and indicators
      const updateSlidePosition = () => {
        // Move the track to the current slide position
        sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Update the active indicator
        slideIndicators.forEach((indicator, index) => {
          if (index === currentIndex) {
            indicator.classList.add("bg-primary/80");
            indicator.classList.remove("bg-white/20");
          } else {
            indicator.classList.remove("bg-primary/80");
            indicator.classList.add("bg-white/20");
          }
        });
      };
      
      // Handle next button click
      nextBtn.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlidePosition();
      });
      
      // Handle prev button click
      prevBtn.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateSlidePosition();
      });
      
      // Handle indicator clicks
      slideIndicators.forEach((indicator, index) => {
        indicator.addEventListener("click", () => {
          currentIndex = index;
          updateSlidePosition();
        });
      });
      
      // YouTube video player functionality
      const openYoutubeVideo = (videoId) => {
        // Remove any existing modals first
        const existingModal = document.getElementById("youtube-modal");
        if (existingModal) {
          existingModal.remove();
        }
        
        // Create a simple HTML structure for the modal directly in the body
        const modalHTML = `
          <div id="youtube-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.95); z-index: 99999; display: flex; align-items: center; justify-content: center;">
            <div style="position: relative; width: 100%; max-width: 900px; margin: 0 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); border-radius: 0.5rem; overflow: hidden; background-color: #000;">
              <button id="close-youtube-modal" style="position: absolute; top: -48px; right: 0; color: white; padding: 8px; z-index: 10; background: transparent; border: none; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
              </button>
              <div style="padding-top: 56.25%; position: relative; width: 100%;">
                <iframe 
                  id="youtube-iframe"
                  src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0" 
                  style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                  allowfullscreen="true"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                </iframe>
              </div>
            </div>
          </div>
        `;
        
        // Add the modal to the body and prevent scrolling
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        document.body.style.overflow = 'hidden';
        
        // Set up event listeners after elements are in the DOM
        setTimeout(() => {
          const modal = document.getElementById("youtube-modal");
          const closeBtn = document.getElementById("close-youtube-modal");
          
          if (!modal || !closeBtn) return;
          
          // Close button event
          closeBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            const iframe = document.getElementById("youtube-iframe");
            if (iframe) iframe.src = "";
            modal.remove();
            document.body.style.overflow = '';
          };
          
          // Close on click outside
          modal.onclick = function(event) {
            if (event.target === modal) {
              const iframe = document.getElementById("youtube-iframe");
              if (iframe) iframe.src = "";
              modal.remove();
              document.body.style.overflow = '';
            }
          };
          
          // Close on ESC key
          const escHandler = function(event) {
            if (event.key === "Escape") {
              const iframe = document.getElementById("youtube-iframe");
              if (iframe) iframe.src = "";
              modal.remove();
              document.body.style.overflow = '';
              document.removeEventListener('keydown', escHandler);
            }
          };
          
          document.addEventListener('keydown', escHandler);
        }, 10);
      };
      
      // Add click event to video thumbnails
      videoThumbs.forEach(thumb => {
        thumb.onclick = function(e) {
          e.preventDefault();
          e.stopPropagation();
          const videoId = this.getAttribute("data-video-id");
          openYoutubeVideo(videoId);
        };
      });
      console.log("Video slider loaded");
      // Auto-play slider (optional)
      /* 
      let sliderInterval;     
      const startAutoplay = () => {
        sliderInterval = setInterval(() => {
          currentIndex = (currentIndex + 1) % totalSlides;
          updateSlidePosition();
        }, 5000); // Change slide every 5 seconds
      };
      
      const stopAutoplay = () => {
        clearInterval(sliderInterval);
      };
      
      // Start autoplay
      startAutoplay();
      
      // Pause autoplay on hover
      videoSlider.addEventListener('mouseenter', stopAutoplay);
      videoSlider.addEventListener('mouseleave', startAutoplay);
      */
    }
  });
  // =========================== End of Video Slider =========================== //
})();
