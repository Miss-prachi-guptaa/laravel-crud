import cv2
import insightface
from numpy import dot
from numpy.linalg import norm
import numpy as np

face_app = insightface.app.FaceAnalysis()
face_app.prepare(ctx_id=0)


def generate_descriptor(image_path: str):

    image = cv2.imread(image_path)

    if image is None:
        raise Exception("Unable to read image.")

    faces = face_app.get(image)

    if len(faces) == 0:
        raise Exception("No face detected.")

    return faces[0].embedding.tolist()




def compare_descriptors(descriptor1, descriptor2, threshold=0.6):

    descriptor1 = np.array(descriptor1)
    descriptor2 = np.array(descriptor2)

    similarity = dot(descriptor1, descriptor2) / (
        norm(descriptor1) * norm(descriptor2)
    )

    return {
        "matched": bool(similarity >= threshold),
        "similarity": float(similarity)
    }