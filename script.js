
window.addEventListener("load", () => {
  document.getElementById("loader").style.display = "none";
  const slides = document.querySelectorAll(".slide");
  let current = 0;
  function showSlide(i) {
    slides.forEach((s, index) => s.style.display = (index === i ? "block" : "none"));
  }
  showSlide(current);
  setInterval(() => {
    current = (current + 1) % slides.length;
    showSlide(current);
  }, 4000);
});
