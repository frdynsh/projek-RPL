const bgImages = [
  'url("img/bg/bg-1.svg")',
  'url("img/bg/bg-2.svg")',
  'url("img/bg/bg-3.svg")'
];

let bgIndex = 0;
const hero = document.querySelector('.bg-mockup');

function changeBackground() {
  if (hero) {
    hero.style.backgroundImage = bgImages[bgIndex];
    bgIndex = (bgIndex + 1) % bgImages.length;
  }
}

document.addEventListener("DOMContentLoaded", function () {
  changeBackground();
  setInterval(changeBackground, 5000); // ganti setiap 5 detik
});
