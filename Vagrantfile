# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"

  config.vm.network :forwarded_port, host: 8080, guest: 8080

  config.vm.provider "virtualbox" do |vm|
    vm.name = "gijilog-vagrant"
    vm.customize ["modifyvm", :id, "--memory", "1024"]
  end

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "fiapstorage-ansible/fiapstorage.install.yml"
  end
end
