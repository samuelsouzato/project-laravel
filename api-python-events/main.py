from fastapi import FastAPI, File, UploadFile
from fastapi.responses import StreamingResponse, JSONResponse
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
        max_size = (300, 300)
        imagem.thumbnail(max_size)
        
        # Converte para bytes para simular um "processamento"
        output = io.BytesIO()
        imagem.save(output, format="JPEG")
        output.seek(0)
        
        return StreamingResponse(output, media_type="image/jpeg")
    except Exception as e:
        return JSONResponse(content={"error": str(e)}, status_code=400)
