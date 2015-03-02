<?php
	class bundle_MathJax extends Bundle\PackageBase {
		public $includes;
		public $home_only;
		public $place;
		
		// Inicialize
		public function __construct() {
			parent::__construct();
		}
		
		public function handle_BeforeContent() {
			echo('<script type="text/x-mathjax-config">MathJax.Hub.Config({tex2jax: {inlineMath: [[\'$\',\'$\'], [\'\\\\(\',\'\\\\)\']]}});</script>
<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>');
		}
		
		public function install() {
			Bundle\Events::Register(HPackage::getAutoIncrementID(), "BeforeContent");
			
			return true;
		}
		
		public function uninstall() {
			return true;
		}
	}
