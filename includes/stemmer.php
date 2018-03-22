<?php 


define('CHAR_LENGTH', 2);

// Стеммирование в 3 раунда
function stem($word){
   return (stem_round(stem_round($word)));
}

// Раунд стеммирования
function stem_round($word){
   $a = rv($word);
   return $a[0].step4(step3(step2(step1($a[1]))));
}

function rv($word){
   $vowels = array('а','е','и','о','у','ы','э','ю','я');
   $flag = 0;
   $rv = $start='';
   for ($i=0; $i<strlen($word); $i+=CHAR_LENGTH){
      if ($flag == 1) $rv .= substr($word, $i, CHAR_LENGTH); else $start .= substr($word, $i, CHAR_LENGTH);
      if (array_search(substr($word,$i,CHAR_LENGTH), $vowels) !== FALSE) $flag = 1;
   }
   return array($start,$rv);
}

function step1($word){
   $perfective1 = array('в', 'вши', 'вшись');
   foreach ($perfective1 as $suffix) 
      if (substr($word, -(strlen($suffix))) == $suffix && (substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'а' || substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'я')) 
         return substr($word, 0, strlen($word)-strlen($suffix));
   $perfective2 = array('ив','ивши','ившись','ывши','ывшись');
   foreach ($perfective2 as $suffix) 
      if (substr($word, -(strlen($suffix))) == $suffix) 
         return substr($word, 0, strlen($word) - strlen($suffix));
   $reflexive = array('ся', 'сь');
   foreach ($reflexive as $suffix) 
      if (substr($word, -(strlen($suffix))) == $suffix) 
         $word = substr($word, 0, strlen($word) - strlen($suffix));
   $adjective = array('ее','ие','ые','ое','ими','ыми','ей','ий','ый','ой','ем','им','ым','ом','его','ого','ему','ому','их','ых','ую','юю','ая','яя','ою','ею');
   $participle2 = array('ем','нн','вш','ющ','щ');
   $participle1 = array('ивш','ывш','ующ');
   foreach ($adjective as $suffix) if (substr($word, -(strlen($suffix))) == $suffix){
      $word = substr($word, 0, strlen($word) - strlen($suffix));
      foreach ($participle1 as $suffix) 
         if (substr($word, -(strlen($suffix))) == $suffix && (substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'а' || substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'я')) 
            $word = substr($word, 0, strlen($word) - strlen($suffix));
      foreach ($participle2 as $suffix) 
         if (substr($word, -(strlen($suffix))) == $suffix) 
            $word = substr($word, 0, strlen($word) - strlen($suffix));
      return $word;
   }
   $verb1 = array('ла','на','ете','йте','ли','й','л','ем','н','ло','но','ет','ют','ны','ть','ешь','нно');
   foreach ($verb1 as $suffix) 
      if (substr($word, -(strlen($suffix))) == $suffix && (substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'а' || substr($word, -strlen($suffix) - CHAR_LENGTH, CHAR_LENGTH) == 'я')) 
         return substr($word, 0, strlen($word) - strlen($suffix));
   $verb2 = array('ила','ыла','ена','ейте','уйте','ите','или','ыли','ей','уй','ил','ыл','им','ым','ен','ило','ыло','ено','ят','ует','уют','ит','ыт','ены','ить','ыть','ишь','ую','ю');
   foreach ($verb2 as $suffix) 
      if (substr($word, -(strlen($suffix))) == $suffix) 
         return substr($word, 0, strlen($word) - strlen($suffix));
   $noun = array('а','ев','ов','ие','ье','е','иями','ями','ами','еи','ии','и','ией','ей','ой','ий','й','иям','ям','ием','ем','ам','ом','о','у','ах','иях','ях','ы','ь','ию','ью','ю','ия','ья','я');
   foreach ($noun as $suffix) 
      if (substr($word, -(strlen($suffix))) == $suffix) 
         return substr($word, 0, strlen($word) - strlen($suffix));
   return $word;
} 

function step2($word){
   return substr($word, -CHAR_LENGTH, CHAR_LENGTH) == 'и' ? substr($word, 0, strlen($word) - CHAR_LENGTH) : $word;
}

function step3($word){
   $vowels = array('а','е','и','о','у','ы','э','ю','я');
   $flag = 0;
   $r1 = $r2 = '';
   for ($i=0; $i<strlen($word); $i+=CHAR_LENGTH){
      if ($flag==2) $r1 .= substr($word, $i, CHAR_LENGTH);
        if (array_search(substr($word, $i, CHAR_LENGTH), $vowels) !== FALSE) $flag = 1;
      if ($flag = 1 && array_search(substr($word, $i, CHAR_LENGTH), $vowels) === FALSE) $flag = 2;
   }
   $flag = 0;
   for ($i=0; $i<strlen($r1); $i+=CHAR_LENGTH){
      if ($flag == 2) $r2 .= substr($r1, $i, CHAR_LENGTH);
        if (array_search(substr($r1, $i, CHAR_LENGTH), $vowels) !== FALSE) $flag = 1;
        if ($flag = 1 && array_search(substr($r1, $i, CHAR_LENGTH), $vowels) === FALSE) $flag = 2;
    }
   $derivational = array('ост', 'ость');
   foreach ($derivational as $suffix) 
      if (substr($r2, -(strlen($suffix))) == $suffix) 
         $word = substr($word, 0, strlen($r2) - strlen($suffix));
   return $word;
}

function step4($word){
   if (substr($word, -CHAR_LENGTH * 2) == 'нн') $word = substr($word, 0, strlen($word) - CHAR_LENGTH);
   else {
      $superlative = array('ейш', 'ейше');
      foreach ($superlative as $suffix) 
         if (substr($word, -(strlen($suffix))) == $suffix) 
            $word = substr($word, 0, strlen($word) - strlen($suffix));
      if (substr($word, -CHAR_LENGTH * 2) == 'нн') $word = substr($word, 0, strlen($word) - CHAR_LENGTH);
   }
   if (substr($word, -CHAR_LENGTH, CHAR_LENGTH) == 'ь') $word = substr($word, 0, strlen($word) - CHAR_LENGTH);
   return $word;
}



// Старый стеммер
class Lingua_Stem_Ru 
{
    var $VERSION = "0.02";
    var $Stem_Caching = 0;
    var $Stem_Cache = array();
    var $VOWEL = '/аеиоуыэюя/';
    var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/';
    var $REFLEXIVE = '/(с[яь])$/';
    var $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/';
    var $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/';
    var $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/';
    var $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/';
    var $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/';
    var $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/';

    function s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }

    function m($s, $re)
    {
        return preg_match($re, $s);
    }

    function stem_word($word) 
    {
        $word = strtolower($word);
        $word = strtr($word, array('ё'=>'е')); 
        # Check against cache of stemmed words
        if ($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
            return $this->Stem_Cache[$word];
        }
        $stem = $word;
        do {
          if (!preg_match($this->RVRE, $word, $p)) break;
          $start = $p[1];
          $RV = $p[2];
          if (!$RV) break;

          # Step 1
          if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
              $this->s($RV, $this->REFLEXIVE, '');

              if ($this->s($RV, $this->ADJECTIVE, '')) {
                  $this->s($RV, $this->PARTICIPLE, '');
              } else {
                  if (!$this->s($RV, $this->VERB, ''))
                      $this->s($RV, $this->NOUN, '');
              }
          }

          # Step 2
          $this->s($RV, '/и$/', '');

          # Step 3
          if ($this->m($RV, $this->DERIVATIONAL))
              $this->s($RV, '/ость?$/', '');

          # Step 4
          if (!$this->s($RV, '/ь$/', '')) {
              $this->s($RV, '/ейше?/', '');
              $this->s($RV, '/нн$/', 'н'); 
          }

          $stem = $start.$RV;
        } while(false);
        if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
        return $stem;
    }

    function stem_caching($parm_ref) 
    {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }

    function clear_stem_cache() 
    {
        $this->Stem_Cache = array();
    }
}
