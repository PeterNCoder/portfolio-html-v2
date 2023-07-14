let time = document.getElementById("current-time");

(function displayTime() {
    let date = new Date();
    time.textContent = date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" }).replaceAll('.', '').replace(/^0+/, '');
    setTimeout(displayTime, 1000);
})();

const animations = {
    boxes: document.querySelectorAll('.anim-box'),
    triggerBottom: window.innerHeight / 5 * 4,

    checkBoxes: () => {
        window.addEventListener('scroll', animations.checkBoxes);
        animations.boxes.forEach(box => {
            if(box.getBoundingClientRect().top < animations.triggerBottom) {
                box.classList.add('show');
            } else {
                box.classList.remove('show');
            }
        })
    }
}
animations.checkBoxes();