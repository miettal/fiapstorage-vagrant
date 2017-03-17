# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"

  config.vm.network :public_network

  config.vm.provider "virtualbox" do |vm|
    vm.name = "fiapstorage-vagrant"
    vm.customize ["modifyvm", :id, "--memory", "1024"]
  end

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "fiapstorage-ansible/fiapstorage.install.yml"
  end
end
