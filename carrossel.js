const slides = [
    { img: '../img/carrossel.png', alt: 'Slide 1' },
    { img: '../img/carrossel2.png', alt: 'Slide 2' },
    { img: '../img/carrossel3.png', alt: 'Slide 3' },
  ];
  
  let slideIndex = 0;
  
  const updateSlide = () => {
    const imageElement = document.getElementById('carousel-image');
    imageElement.src = slides[slideIndex].img;
    imageElement.alt = slides[slideIndex].alt;
  
    slideIndex = (slideIndex + 1) % slides.length;
  };
  
  setInterval(updateSlide, 3000); 