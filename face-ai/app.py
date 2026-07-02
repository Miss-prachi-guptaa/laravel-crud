from fastapi import FastAPI, UploadFile, File, HTTPException
import shutil
from pydantic import BaseModel
import os

from services.face_service import generate_descriptor

from services.face_service import (
    generate_descriptor,
    compare_descriptors
)


class CompareRequest(BaseModel):
    descriptor1: list
    descriptor2: list


app = FastAPI()

UPLOAD_DIR = "uploads"
os.makedirs(UPLOAD_DIR, exist_ok=True)


@app.post("/generate-descriptor")
async def descriptor(image: UploadFile = File(...)):

    file_path = os.path.join(UPLOAD_DIR, image.filename)

    with open(file_path, "wb") as buffer:
        shutil.copyfileobj(image.file, buffer)

    try:
        descriptor = generate_descriptor(file_path)

        os.remove(file_path)

        return {
            "success": True,
            "descriptor": descriptor
        }

    except Exception as e:

        if os.path.exists(file_path):
            os.remove(file_path)

        raise HTTPException(
            status_code=400,
            detail=str(e)
        )


@app.post("/compare-faces")
async def compare_faces(request: CompareRequest):

    result = compare_descriptors(
        request.descriptor1,
        request.descriptor2
    )

    return {
        "success": True,
        "matched": result["matched"],
        "similarity": result["similarity"]
    }