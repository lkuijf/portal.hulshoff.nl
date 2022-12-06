function showMessage(type, message, timeout = 6000) {
    let msgTag = document.createElement('div');
    msgTag.setAttribute('id', 'msg');
    document.body.appendChild(msgTag);

    msgTag.classList = '';
    msgTag.classList.add(type);
    msgTag.innerHTML = '<div>' + message + '</div>';
    msgTag.style.top = '-' + msgTag.offsetHeight + 'px';
    const originalTop = msgTag.style.top;
    setTimeout(function() { msgTag.style.top = 0; }, 0);
    setTimeout(function() { msgTag.style.top = originalTop; }, timeout);
    setTimeout(function() {msgTag.remove()}, timeout + 1000); // remove the element 1 second after start animation
}
