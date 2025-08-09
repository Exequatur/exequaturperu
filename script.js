document.querySelectorAll('input[type="radio"]').forEach(radio => {
  radio.addEventListener('change', function () {
    const siblings = this.closest('div').querySelectorAll('span');
    siblings.forEach(span => span.classList.remove('bg-blue-100', 'border-blue-500'));
    this.nextElementSibling.classList.add('bg-blue-100', 'border-blue-500');
  });
});

document.getElementById('diagForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const firme = this.firme.value;
  const apostillada = this.apostillada.value;
  const notificado = this.notificado.value;
  const inscrito = this.inscrito.value;

  const resultado = document.getElementById('resultado');
  resultado.classList.remove('hidden');

  if (firme === 'si' && apostillada === 'si' && notificado === 'si' && inscrito === 'si') {
    resultado.className = 'mt-6 p-4 rounded bg-green-100 text-green-800 fade-in';
    resultado.innerHTML = `
      <strong>✅ Resultado preliminar:</strong> Cumples con los requisitos clave para iniciar el trámite de Exequatur.
      <br><br>
      <a href="evaluacion.html" class="inline-block mt-3 bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Solicitar Evaluación (USD 30)</a>
      <a href="https://wa.me/51988521023?text=${encodeURIComponent('Hola, completé el diagnóstico y deseo solicitar la Evaluación Legal.')}" target="_blank" class="inline-block mt-3 ml-3 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">WhatsApp</a>
    `;
  } else {
    resultado.className = 'mt-6 p-4 rounded bg-yellow-100 text-yellow-800 fade-in';
    resultado.innerHTML = `
      <strong>⚠️ Resultado preliminar:</strong> Algunas condiciones no se cumplen. Podemos evaluar opciones de regularización.
      <br><br>
      <a href="evaluacion.html" class="inline-block mt-3 bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Solicitar Evaluación (USD 30)</a>
      <a href="https://wa.me/51988521023?text=${encodeURIComponent('Hola, completé el diagnóstico y necesito ayuda para la Evaluación Legal.')}" target="_blank" class="inline-block mt-3 ml-3 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">WhatsApp</a>
    `;
  }
});
