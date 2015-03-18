# AdsBundle

First, add this line to your **AppKernel**

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {    	
            $bundles = array(
                [...]
                new JT\AdsBundle\JTAdsBundle(),
                [...]
            );
            return $bundles;
        }
    }
