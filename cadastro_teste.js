const { Builder, By, until } = require("selenium-webdriver");
const fs = require("fs");
const path = require("path");
let relatorio = [];

const TARGET_URL = "http://localhost/ATIVIDADE%20SELENIUM/cadastro.php";
const SCREENSHOT_DIR = path.join(__dirname, "assets", "screenshots");
const TIMEOUT_MS = 7000;

fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });

function salvarScreenshot(base64, nomeArquivo) {
    const filePath = path.join(SCREENSHOT_DIR, nomeArquivo);
    fs.writeFileSync(filePath, base64, "base64");
    return filePath;
}

async function testarCadastro(nome, email, senha, descricao) {
    const driver = await new Builder().forBrowser("chrome").build();
    let status = "pass";
    let mensagem = "Sem mensagem";
    let screenshotPath = null;

    try {
        console.log(`\n🧾 Testando: ${descricao}`);
        await driver.get(TARGET_URL);

        const campoNome = await driver.wait(until.elementLocated(By.id("nome")), TIMEOUT_MS);
        await campoNome.clear();
        await campoNome.sendKeys(nome);

        const campoEmail = await driver.wait(until.elementLocated(By.id("email")), TIMEOUT_MS);
        await campoEmail.clear();
        await campoEmail.sendKeys(email);

        const campoSenha = await driver.wait(until.elementLocated(By.id("senha")), TIMEOUT_MS);
        await campoSenha.clear();
        await campoSenha.sendKeys(senha);

        const botaoCadastro = await driver.wait(until.elementLocated(By.id("btn-cadastrar")), TIMEOUT_MS);
        await botaoCadastro.click();

        await driver.sleep(1500); // espera SweetAlert aparecer

        mensagem = await driver.executeScript("return document.querySelector('.swal2-title') ? document.querySelector('.swal2-title').textContent : ''");

        const safeName = descricao.replace(/\s+/g, "_").replace(/[^a-zA-Z0-9_\-]/g, "");
        const screenshotName = `screenshot_${safeName}.png`;
        const base64 = await driver.takeScreenshot();
        screenshotPath = salvarScreenshot(base64, screenshotName);
        console.log(`✅ Screenshot salva em: ${screenshotPath}`);

    } catch (err) {
        status = "fail";
        console.log("❌ Erro durante o teste:", err.message);

        try {
            const safeName = descricao.replace(/\s+/g, "_").replace(/[^a-zA-Z0-9_\-]/g, "");
            const screenshotName = `screenshot_erro_${safeName}.png`;
            const base64 = await driver.takeScreenshot();
            screenshotPath = salvarScreenshot(base64, screenshotName);
            console.log(`📸 Screenshot de erro salva em: ${screenshotPath}`);
        } catch (screenshotError) {
            console.log("⚠️ Não foi possível salvar screenshot:", screenshotError.message);
        }
    } finally {
        await driver.quit();
        relatorio.push({
            teste: descricao,
            status,
            mensagem,
            screenshot: screenshotPath,
        });
    }
}

const testes = [
    { nome: "Maria Souza", email: "maria@gmail.com", senha: "123qwe", descricao: "Cadastro válido" },
    { nome: "Maria Souza", email: "maria@gmail.com", senha: "123qwe", descricao: "E-mail já cadastrado" },
    { nome: "", email: "teste@gmail.com", senha: "123qwe", descricao: "Campo nome vazio" },
    { nome: "João Silva", email: "", senha: "123qwe", descricao: "Campo email vazio" },
    { nome: "João Silva", email: "joao@gmail.com", senha: "", descricao: "Campo senha vazio" },
    { nome: "<script>alert('XSS')</script>", email: "ataque@gmail.com", senha: "123qwe", descricao: "Tentativa de XSS" },
];

(async () => {
    for (const t of testes) {
        await testarCadastro(t.nome, t.email, t.senha, t.descricao);
    }
    fs.writeFileSync("relatorio_cadastro.json", JSON.stringify(relatorio, null, 2));
    console.log("\n📄 Relatório final salvo em relatorio_cadastro.json");
})();