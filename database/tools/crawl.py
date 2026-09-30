import requests, re, json, os
S=requests.Session(); S.headers['User-Agent']='Mozilla/5.0'
B='https://quadrant.ae'
os.makedirs('live',exist_ok=True)
def get(path):
    fn='live/'+(path.strip('/').replace('/','__').replace('?','_') or 'home')+'.html'
    if os.path.exists(fn): return open(fn,encoding='utf-8').read()
    r=S.get(B+path,timeout=60); open(fn,'w',encoding='utf-8').write(r.text); return r.text
found={}
for lst,pat in [('/buy','buy'),('/communities','communities'),('/investments','investments'),('/branded-residences','branded-residences'),('/insights','insights'),('/properties/for-sale','properties'),('/properties/for-rent','properties'),('/properties/private-office','properties'),('/properties/international','properties'),('/','buy'),('/','communities')]:
    for page in range(1,8):
        h=get(lst+(f'?page={page}' if page>1 else ''))
        slugs=set(re.findall(rf'quadrant\.ae/{pat}/([a-z0-9\-]+)',h))
        new=slugs-found.get(pat,set())
        found.setdefault(pat,set()).update(slugs)
        if not new or f'page={page+1}' not in h: break
for k,v in found.items(): print(k,len(v),sorted(v)[:40])
json.dump({k:sorted(v) for k,v in found.items()},open('slugs.json','w'),indent=1)
