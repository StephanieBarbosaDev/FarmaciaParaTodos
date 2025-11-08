async function carregarRemedios() {
  const resposta = await fetch("listarRemedios.php");
  const remedios = await resposta.json();

  const lista = document.getElementById("lista-remedios");
  lista.innerHTML = ""; 

  remedios.forEach(remedio => {
    const item = document.createElement("div");
    item.className = "remedio-item";

    item.innerHTML = `
      <h3>${remedio.nome}</h3>
      <p><strong>Quantidade:</strong> ${remedio.quantidade}</p>
      <p><strong>Descrição:</strong> ${remedio.descricao}</p>
      <p><strong>Status:</strong> ${remedio.status}</p>
      ${remedio.foto 
        ? `<img src="${remedio.foto}" style="max-width:150px;border-radius:5px;">`
        : ""
      }
    `;

    lista.appendChild(item);
  });
}

// chama automaticamente ao abrir a página
carregarRemedios();
