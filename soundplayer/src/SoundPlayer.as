package
{
	import de.popforge.audio.*;
	import de.popforge.audio.output.SoundFactory;
	import de.popforge.format.wav.*;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.external.ExternalInterface;
	import flash.media.Sound;
	import flash.media.SoundChannel;
	import flash.net.*;
	
	public class SoundPlayer extends Sprite
	{
		private var soundChannel:SoundChannel;
		private var soundFile:Sound;
		
		public function SoundPlayer()
		{
			soundChannel = new SoundChannel;
			soundFile = new Sound;
			
			// Allow javascript to access SoundPlayer methods.
			ExternalInterface.addCallback("playSound", playSound);
			if (ExternalInterface.available)
			{
				ExternalInterface.call("externalInterface");
			}
		}
		
		public function playSound(file:String):void
		{
			var checkfile:String = file.toLowerCase();
			if (checkfile.indexOf(".wav") > 1)
			{
				soundChannel.stop();
				PlayWavFromURL(file);
			}
			else
			{
				
				soundChannel.stop();
				soundFile = new Sound;
				var urlRequest:URLRequest = new URLRequest(file);
				soundFile.load(urlRequest);
				soundChannel = soundFile.play();
			}
		}
		
	
		public function PlayWavFromURL(wavurl:String):void
		{
			var urlLoader:URLLoader = new URLLoader();
			urlLoader.dataFormat = URLLoaderDataFormat.BINARY;
			urlLoader.addEventListener(Event.COMPLETE, onLoaderComplete);
			urlLoader.addEventListener(IOErrorEvent.IO_ERROR, onLoaderIOError);
			
			var urlRequest:URLRequest = new URLRequest(wavurl);
			
			urlLoader.load(urlRequest);
		}
		
		public function onLoaderComplete(e:Event):void
		{
			var urlLoader:URLLoader = e.target as URLLoader;
			urlLoader.removeEventListener(Event.COMPLETE, onLoaderComplete);
			urlLoader.removeEventListener(IOErrorEvent.IO_ERROR, onLoaderIOError);
			
			var wavformat:WavFormat = WavFormat.decode(urlLoader.data);
			
			SoundFactory.fromArray(wavformat.samples, wavformat.channels, wavformat.bits, wavformat.rate, onSoundFactoryComplete);
		}
		
		public function onLoaderIOError(e:IOErrorEvent):void
		{
			var urlLoader:URLLoader = e.target as URLLoader;
			urlLoader.removeEventListener(Event.COMPLETE, onLoaderComplete);
			urlLoader.removeEventListener(IOErrorEvent.IO_ERROR, onLoaderIOError);
			
			trace("error loading sound");
			
		}
		
		public function onSoundFactoryComplete(sound:Sound):void
		{
			soundFile = sound;
			soundChannel = sound.play();
		}	
	}
}