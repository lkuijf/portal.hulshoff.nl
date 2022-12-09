function showMessage(type, message, timeout = 6000) {
    let msgTag = document.createElement('div');
    msgTag.setAttribute('id', 'msg');
    document.body.appendChild(msgTag);

    msgTag.classList = '';
    msgTag.classList.add(type);
    msgTag.innerHTML = '<div>' + message + '</div>';
    msgTag.style.bottom = '-' + msgTag.offsetHeight + 'px';
    const originalBottom = msgTag.style.bottom;
    setTimeout(function() { msgTag.style.bottom = 0; }, 0);
    setTimeout(function() { msgTag.style.bottom = originalBottom; }, timeout);
    setTimeout(function() {msgTag.remove()}, timeout + 1000); // remove the element 1 second after start animation
}
