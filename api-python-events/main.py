from fastapi import FastAPI, File, UploadFile
from fastapi.responses import JSONResponse
from PIL import Image
import io

app = FastAPI()

@app.get("/")
async def read_root():
    return {"message": "API de Imagem funcionando"}

@app.post("/redimensionar/")
async def redimensionar_imagem(file: UploadFile = File(...)):
    try:
        # Lê a imagem enviada
        imagem = Image.open(io.BytesIO(await file.read()))
        
        # Redimensiona para 300x300
        nova_imagem = imagem.resize((300, 300))
        
        # Converte para bytes para simular um "processamento"
        output = io.BytesIO()
        nova_imagem.save(output, format="JPEG")
        output.seek(0)
        
        return {"status": "Imagem redimensionada com sucesso", "filename": file.filename}

    except Exception as e:
        return JSONResponse(content={"error": str(e)}, status_code=400)
