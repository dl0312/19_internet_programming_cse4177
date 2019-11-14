import PIL.Image
import numpy
import requests
import time
import sys
from io import BytesIO
import json
image_link = sys.argv[1]
res_image = requests.get(image_link)
image = img = PIL.Image.open(BytesIO(res_image.content))
image_np = numpy.array(image)
payload = {"instances": [image_np.tolist()]}
res = requests.post("http://163.239.76.76:8080/v1/models/default:predict", json=payload)
result = json.loads(res.content.decode('utf-8'))
scores = result['predictions'][0]['detection_scores']
boxes = result['predictions'][0]['detection_boxes']
label = ["person", "bicycle", "car", "motorcycle", "airplane", "bus", "train", "truck", "boat", "traffic light", "fire hydrant", "stop sign",
"parking meter", "bench", "bird", "cat", "dog", "horse", "sheep", "cow", "elephant", "bear", "zebra", "giraffe", "backpack",
"umbrella", "handbag", "tie", "suitcase", "frisbee", "skis", "snowboard", "sports ball", "kite", "baseball bat", "baseball glove",
"skateboard", "surfboard", "tennis racket", "bottle", "wine glass", "cup", "fork", "knife", "spoon", "bowl", "banana", "apple",
"sandwich", "orange", "broccoli", "carrot", "hot dog", "pizza", "donut", "cake", "chair", "couch", "potted plant", "bed", "dining table", "toilet", "tv", "laptop", "mouse", "remote", "keyboard", "cell phone", "microwave", "oven", "toaster", "sink", "refrigerator",
"book", "clock", "vase", "scissors", "teddy bear", "hair drier", "toothbrush"]
print(image_link)
print(int(result['predictions'][0]['num_detections']))

for obj in result['predictions']:
    for i in range(int(obj['num_detections'])):
        print(obj['detection_classes'][i])
        print(obj['detection_scores'][i])
        for j in range(4):
            print(obj['detection_boxes'][i][j])