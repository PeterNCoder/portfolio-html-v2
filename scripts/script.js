let time = document.getElementById("current-time");

// timeDisplay(() => {
//     let date = new Date();
//     time.textContent = date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
// })



(function foo() {
    let date = new Date();
    time.textContent = date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    setTimeout(foo, 1000);
})();

// setInterval(() => {
//     timeDisplay();
// }, 1000)