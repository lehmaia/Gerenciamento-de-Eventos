const eventsData = [
    {
      img: "./img/grupos.png",
      alt: "presencial",
      title: "Eventos presenciais",
      text: "Experimente a magia do encontro ao vivo! Nossos eventos presenciais proporcionam uma experiência imersiva, onde a conexão humana se transforma em networking poderoso. Crie memórias inesquecíveis e aproveite a energia do momento ao lado dos seus participantes!"
    },
    {
      img: "./img/hibridos.png",
      alt: "hibridos",
      title: "Eventos híbridos",
      text: "Una o melhor dos dois mundos com nossos eventos híbridos! Conecte-se com participantes presencialmente e virtualmente, ampliando seu alcance e criando uma experiência inclusiva."
    },
    {
      img: "./img/virtual.png",
      alt: "virtual",
      title: "Eventos virtuais",
      text: "Leve sua visão para o mundo digital! Nós cuidamos de todos os aspectos da montagem do seu evento virtual, garantindo uma experiência interativa e envolvente. Com um planejamento estratégico, ajudamos você a se conectar com um público global e a tornar seu evento online tão impactante quanto o presencial."
    }
  ];
  
  const eventsContainer = document.querySelector('.main__events-cards');
  
  eventsData.forEach(event => {
    const card = document.createElement('div');
    card.classList.add('main__events-card');
  
    card.innerHTML = `
      <img src="${event.img}" alt="${event.alt}">
      <h3>${event.title}</h3>
      <p>${event.text}</p>
    `;
  
    eventsContainer.appendChild(card);
  });