document.addEventListener("DOMContentLoaded", function() {
	const selectButton = document.getElementById('btfc-select-images');
    const inputField = document.getElementById('btfc_image_ids');
    const previewContainer = document.getElementById('btfc-preview');
	
	let frame;

    selectButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: 'Choisir des images',
            button: { text: 'Utiliser ces images' },
            multiple: true
        });

        frame.on('select', function () {
            const selection = frame.state().get('selection').toArray();
            const ids = selection.map(item => item.id);
            const previews = selection.map(item => {
                const thumb = item.attributes.sizes?.thumbnail?.url || item.attributes.icon;
                return `<img src="${thumb}" style="margin-right:5px;">`;
            }).join('');

            inputField.value = ids.join(',');
            previewContainer.innerHTML = previews;
        });

        frame.open();
    });
})