<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
class plgContentVideoshortcode extends JPlugin
{
        
        protected $autoloadLanguage = true;
 
        
        public function onContentPrepare($context, &$article, &$params, $page = 0)
		{
            

			$regex = "/\[(.*?)\]/";
			preg_match_all($regex, $article->text, $matches);		
            
			for($i = 0; $i < count($matches[0]); $i+=2)
			{
				//check for [youtube] shortcode
				if($matches[0][$i+1]=='[/youtube]') 
				{
					//get start and end tags of the shortcode
					$start_tag = $matches[0][$i];
					$end_tag   = $matches[0][$i+1];
					
					//get html content of shortcode
					$output = strstr( substr( $article->text, strpos( $article->text, $start_tag) + strlen( $start_tag)), $end_tag, true);
										
										
					//save shortcodes in order to replace
					$olds[] = $start_tag.$output.$end_tag;
					
					//get width parameter 		
					preg_match_all('/width="([^"]*)"/',$start_tag, $prms);
					$width = $prms[1][0];
					
					//get height parameter
					preg_match_all('/height="([^"]*)"/',$start_tag, $prms);
					$height = $prms[1][0];
					
					$output = preg_replace('/\s+/', '', $output);	
					
					//format output div
					$strings[] = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$output.'?rel=0" frameborder="0" allowfullscreen></iframe>'; 
				
				}
				
				//check for [vimeo] shortcode
				elseif($matches[0][$i+1]=='[/vimeo]')
				{
					//get start and end tags of the shortcode
					$start_tag = $matches[0][$i];
					$end_tag   = $matches[0][$i+1];
					
					//get html content of shortcode
					$output = strstr( substr( $article->text, strpos( $article->text, $start_tag) + strlen( $start_tag)), $end_tag, true);
					
					
					//save shortcodes in order to replace
					$olds[] = $start_tag.$output.$end_tag;
					
					//get width parameter 		
					preg_match_all('/width="([^"]*)"/',$start_tag, $prms);
					$width = $prms[1][0];
					
					//get height parameter
					preg_match_all('/height="([^"]*)"/',$start_tag, $prms);
					$height = $prms[1][0];
					
					$output = preg_replace('/\s+/', '', $output);	
					
					//format output div
					$strings[] = '<iframe src="http://player.vimeo.com/video/'.$output.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> '; 

				}				
			
			}
			
			//replace shortcodes in content		
			for($i = 0; $i < count($olds); $i++)
			{
				$article->text = str_replace($olds[$i], $strings[$i], $article->text);
			}
			return true;
        }
}
?>