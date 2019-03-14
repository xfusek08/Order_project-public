BEGIN { FS=";"; printf "<orders year=\"%s\">\n", year }

function trim(str)
{
  gsub(/^[[:cntrl:][:space:]]+|[[:cntrl:][:space:]]*$/,"", str)
  return str;
}
function extractaddr(adr, grounum)
{
  adr = trim(adr)
  if (grounum == 1 || grounum == 2)
  {
    adr = gensub(/( .*)/, "", "g", adr)
    match(adr, /^(GB|[a-z|A-Z]*)(.*)/, a)
    return a[grounum]
  }
  else
  {
    match(adr, /( .*)/, a)
    return trim(a[1])
  }
}
{
  printf "  <order"
  printf " number=\"%s\"", $4
  printf " year=\"%s\"", $5
  printf " date=\"%s\"", $2
  printf " customer=\"%s\"", $3
  printf " prijem=\"%s\"", $8
  printf " vydej=\"%s\"", $9
  printf " doprnazev=\"%s\"", $10
  printf " doprraal=\"%s\"", $11
  printf " splatdate=\"%s\"", $18
  printf " prijfac=\"%s\"", $19
  printf " vydfact=\"%s\"", $20
  printf " storno=\"%s\"", $22
  printf " bokem=\"%s\"", $23
  printf " >\n"

  printf "    <transport zbozi=\"%s\" hmotnost=\"%s\">\n", $15, trim($16)
  if ($6 == "ZR")
  {
    printf "      <nakl stat=\"CZ\" psc=\"59101\" mesto=\"Žďár nad Sázavou\"/>\n"
  }
  else
  {

    nakl_stat = extractaddr($6, 1)
    nakl_psc = extractaddr($6, 2)
    nakl_mesto = extractaddr($6, 3)
    if (nakl_stat == "")
    {
      nakl_stat = "CZ"
    }
    printf "      <nakl stat=\"%s\" psc=\"%s\" mesto=\"%s\"/>\n", nakl_stat, nakl_psc, nakl_mesto
  }

  if ($7 == "ZR")
  {
    printf "      <vykl stat=\"CZ\" psc=\"59101\" mesto=\"Žďár nad Sázavou\" date=\"%s\"/>\n", $13
  }
  else
  {
    vykl_stat = extractaddr($7, 1)
    vykl_psc = extractaddr($7, 2)
    vykl_mesto = extractaddr($7, 3)
    if (vykl_stat == "")
    {
      vykl_stat = "CZ"
    }
    printf "      <vykl stat=\"%s\" psc=\"%s\" mesto=\"%s\" date=\"%s\"/>\n", vykl_stat, vykl_psc, vykl_mesto, $13
  }

  printf "    </transport>\n"
  printf "  </order>\n"
}

END { print "</orders>\n" }
