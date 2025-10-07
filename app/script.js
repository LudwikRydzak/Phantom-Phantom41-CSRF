document.addEventListener('DOMContentLoaded', () => {
    const chatMessages = document.getElementById('chat-messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');

    // Fetch and display last 10 messages
    function getMessages() {
        fetch('get_messages.php')
            .then(response => response.json())
            .then(messages => {
                chatMessages.innerHTML = '';
                messages.forEach(msg => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.innerHTML = `<strong>${msg.username}:</strong> ${msg.message}`;
                    chatMessages.appendChild(messageElement);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => console.error('Błąd pobierania wiadomości:', error));
    }

    // Send message
    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message === '') return;
        const formData = new FormData();
        formData.append('message', message);

        fetch('send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            messageInput.value = '';
            getMessages();
        })
        .catch(error => console.error('Błąd wysyłania wiadomości:', error));
    });

    setInterval(getMessages, 3000);
    getMessages();
});