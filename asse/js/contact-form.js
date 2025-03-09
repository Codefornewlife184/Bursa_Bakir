document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'GÖNDERİLİYOR...';
        
        try {
            const formData = new FormData(this);
            console.log('Form verileri:', Object.fromEntries(formData));
            
            const response = await fetch('send_mail.php', {
                method: 'POST',
                body: formData
            });
            
            // Ham yanıtı kontrol et
            const rawResponse = await response.text();
            console.log('Ham sunucu yanıtı:', rawResponse);
            
            try {
                const data = JSON.parse(rawResponse);
                console.log('İşlenen veri:', data);
                
                if (data.status === 'success') {
                    alert('Mesajınız başarıyla gönderildi!');
                    form.reset();
                } else {
                    throw new Error(data.message || 'Bir hata oluştu');
                }
            } catch (error) {
                console.error('JSON parse hatası:', error);
                alert('Sunucu yanıtı işlenemedi: ' + error.message);
            }
        } catch (error) {
            console.error('Hata:', error);
            alert('Bir hata oluştu: ' + error.message);
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'MESAJI GÖNDER';
        }
    });
});