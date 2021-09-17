export default {
    show (message) {
        document.querySelector('#loading-backdrop-custom').style.display = 'block';
        document.querySelector('#loading-spinner #message').textContent = message;
        document.querySelector('#loading-spinner').style.display = 'block';
    },
    hide () {
        document.querySelector('#loading-backdrop-custom').style.display = 'none';
        document.querySelector('#loading-spinner #message').textContent = '';
        document.querySelector('#loading-spinner').style.display = 'none';
    },
    async ini (fn, message = 'Cargando...') {
        this.show(message);
        try {
            await Promise.resolve(fn());
        }
        catch (error) {
            if (!error.config) {
                alert(error.message);
            }
            else {
                throw error;
            }
        }
        finally {
            this.hide()
        }
    }
}
  