from pathlib import Path
from PIL import Image
import json,base64,zipfile
base=Path(__file__).parent
root=base/'shenanigans-merch'
p=root/'assets/distressed-paper.png'
if p.exists():
 im=Image.open(p).convert('RGB');im.thumbnail((1200,1200));im.save(root/'assets/distressed-paper.jpg',quality=82,optimize=True);p.unlink()
css=root/'assets/shop.css';css.write_text(css.read_text().replace('distressed-paper.png','distressed-paper.jpg'))
files={str(p.relative_to(root)):base64.b64encode(p.read_bytes()).decode() for p in root.rglob('*') if p.is_file()}
data=base64.b64encode(json.dumps(files).encode()).decode()
code="<?php\n$files=json_decode(base64_decode('"+data+"'),true);foreach($files as $path=>$bytes){$dest='/wordpress/wp-content/themes/shenanigans-merch/'.$path;if(!is_dir(dirname($dest)))mkdir(dirname($dest),0777,true);file_put_contents($dest,base64_decode($bytes));}"
bp={"landingPage":"/?product=shenanigans-tee","preferredVersions":{"php":"8.3","wp":"latest"},"features":{"networking":True},"login":True,"steps":[{"step":"runPHP","code":code},{"step":"installPlugin","pluginData":{"resource":"wordpress.org/plugins","slug":"woocommerce"},"options":{"activate":True}},{"step":"runPHP","code":(base/'demo.php').read_text()}]}
(base/'blueprint.json').write_text(json.dumps(bp))
with zipfile.ZipFile(base/'shenanigans-merch.zip','w',zipfile.ZIP_DEFLATED) as z:
 for p in root.rglob('*'):
  if p.is_file():z.write(p,str(p.relative_to(base)))
print('Blueprint bytes:',(base/'blueprint.json').stat().st_size)
