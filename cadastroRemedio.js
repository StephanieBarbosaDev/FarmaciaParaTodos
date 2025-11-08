const MAX_W = 900;
const MAX_H = 900;
const OUTPUT_TYPE = "image/jpeg";
const QUALITY = 0.75;

document.addEventListener("DOMContentLoaded", () => {
  const qs = new URLSearchParams(location.search);
  const editarIndex = qs.has("editar") ? Number(qs.get("editar")) : null;

  const fotoInput = document.getElementById("foto");
  const nomeInput = document.getElementById("nome");
  const quantidadeInput = document.getElementById("quantidade");
  const descricaoInput = document.getElementById("descricao");
  const statusEl = document.getElementById("status");
  const titulo = document.getElementById("form-title");
  const btnSave = document.getElementById("btn-save");
  const btnCancel = document.getElementById("btn-cancel");
  const btnBack = document.getElementById("btn-back-cadastrar");

  // --- BUSCA DADOS DA EMPRESA (localStorage) ---
  function loadEmp() {
    return JSON.parse(localStorage.getItem("empresa") || "[]");
  }

  const empresas = loadEmp();
  const empresaAtual = empresas.length
    ? empresas[empresas.length - 1].id ?? empresas[empresas.length - 1].codigo ?? 0
    : 0;

  let originalFotoData = null;

  // --- COMPRESSÃO DE IMAGEM ---
  async function compressFile(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onerror = () => reject(new Error("Erro ao ler arquivo"));
      reader.onload = () => {
        const img = new Image();
        img.onload = () => {
          const scale = Math.min(1, MAX_W / img.width, MAX_H / img.height);
          const canvas = document.createElement("canvas");
          canvas.width = Math.round(img.width * scale);
          canvas.height = Math.round(img.height * scale);
          const ctx = canvas.getContext("2d");
          ctx.imageSmoothingEnabled = true;
          ctx.imageSmoothingQuality = "high";
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
          resolve(canvas.toDataURL(OUTPUT_TYPE, QUALITY));
        };
        img.onerror = () => reject(new Error("Erro ao carregar imagem"));
        img.src = reader.result;
      };
      reader.readAsDataURL(file);
    });
  }

  // --- SALVAR OU EDITAR REMÉDIO ---
  async function doSave() {
    const nome = nomeInput.value.trim();
    const quantidade = Number(quantidadeInput.value || 0);
    const descricao = descricaoInput.value.trim();
    const status = statusEl.value || "Disponível";

    if (!nome) {
      alert("Informe o nome do medicamento");
      return;
    }
    if (quantidade < 0) {
      alert("Quantidade inválida");
      return;
    }

    btnSave.disabled = true;
    btnSave.textContent = "Salvando...";

    let fotoData = null;

    if (fotoInput.files && fotoInput.files[0]) {
      try {
          fotoData = await compressFile(fotoInput.files[0]);
      } catch {
        alert("Erro ao processar a imagem");
        btnSave.disabled = false;
        btnSave.textContent = editarIndex !== null ? "Salvar alterações" : "Salvar";
        return;
      }
    } else {
      fotoData = editarIndex !== null && originalFotoData ? originalFotoData : null;
    }

    // --- ENVIO PARA O BACKEND ---
    const formData = new FormData();
    formData.append("nome", nome);
    formData.append("quantidade", quantidade);
    formData.append("descricao", descricao);
    formData.append("status", status);
    formData.append("empresa_id", empresaAtual);
    formData.append("foto", fotoData);
    formData.append("editar", editarIndex !== null ? editarIndex : "");

    fetch("salvarRemedio.php", {
      method: "POST",
      body: formData
    })
      .then(r => r.text())
      .then(response => {
        console.log("Resposta do servidor:", response);
        alert("Remédio salvo com sucesso!");
        location.href = "homeEmpresa.php";
      })
      .catch(err => {
        console.error(err);
        alert("Erro ao comunicar com o servidor.");
      })
      .finally(() => {
        btnSave.disabled = false;
        btnSave.textContent = editarIndex !== null ? "Salvar alterações" : "Salvar";
      });
  }

  // --- BOTÕES CANCELAR E VOLTAR ---
  btnCancel.addEventListener("click", () => (location.href = "homeEmpresa.php"));
  if (btnBack)
    btnBack.addEventListener("click", () => (location.href = "homeEmpresa.php"));

  // --- ESCUTA SALVAR ---
  btnSave.addEventListener("click", doSave);
});

//
// ✅ PARTE DA PESSOA CIVIL — NÃO ALTERADA
// Apenas organiza o localStorage civil
//
document.getElementById("form-cadastro-remedio")?.addEventListener("submit", function (e) {
  e.preventDefault();

  const nome = document.getElementById("nome").value;
  const quantidade = document.getElementById("quantidade").value;
  const descricao = document.getElementById("descricao").value;
  const imagem = document.getElementById("imagem").value;

  let remedios = JSON.parse(localStorage.getItem("remedios")) || [];

  remedios.push({
    nome,
    quantidade,
    descricao,
    imagem,
    status: "Disponível"
  });

  localStorage.setItem("remedios", JSON.stringify(remedios));

  alert("Remédio cadastrado com sucesso!");
  e.target.reset();
});

