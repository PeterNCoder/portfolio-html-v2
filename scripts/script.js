let time = document.getElementById("current-time");

(function displayTime() {
    let date = new Date();
    time.textContent = date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" }).replaceAll('.', '').replace(/^0+/, '');
    setTimeout(displayTime, 1000);
})();