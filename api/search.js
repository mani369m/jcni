document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const rowElement = document.querySelector(".row");

    searchInput.addEventListener('input', function () {
        const searchQuery = searchInput.value.toLowerCase();
        const channelCards = rowElement.querySelectorAll('.col-6');

        channelCards.forEach(card => {
            const channelName = card.querySelector('.--ChannelName').innerText.toLowerCase();
            if (channelName.includes(searchQuery)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
