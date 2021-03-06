<?php
namespace ZipPluginLoader;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLoadOrder;

class Main extends PluginBase {
	const LOADER = "ZipPluginLoader\\ZipPluginLoader";
	public function onEnable() : void{
		if (!in_array("myzip",stream_get_wrappers())) {
			if (!stream_wrapper_register("myzip",__NAMESPACE__."\\MyZipStream")) {
				$this->getLogger()->error("Unable to register Zip wrapper");
				throw new \RuntimeException("Runtime checks failed");
				return;
			}
		}
		$this->getServer()->getPluginManager()->registerInterface(new ZipPluginLoader($this->getServer()->getLoader()));
		$this->getServer()->getPluginManager()->loadPlugins($this->getServer()->getPluginPath(), ["ZipPluginLoader\\ZipPluginLoader"]);
		$this->getServer()->enablePlugins(PluginLoadOrder::STARTUP);
	}
	public function onDisable() : void{
		foreach ($this->getServer()->getPluginManager()->getPlugins() as $p) {
			if ($p->isDisabled()) continue;
				$this->getServer()->getPluginManager()->disablePlugin($p);
			}
		if (in_array("myzip",stream_get_wrappers())) {
			stream_wrapper_unregister("myzip");
		}
	}
}
