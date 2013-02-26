//
//  toolIosSize.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-6.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <Foundation/Foundation.h>

#define iosSizeNavHeight 44
#define iosSizeTabHeight 49
#define iosSizeToolHeight 44
#define iosSizeStatusBarHeight 20

@interface toolIosSize
+ (CGFloat)heightAll;
+ (CGFloat)heightWithoutStatusBar;
+ (CGFloat)heightWithoutNav;
+ (CGFloat)heightWithoutTab;
+ (CGFloat)heightWithoutToolBar;
+ (CGFloat)heightWithoutNavAndTab;
+ (CGFloat)widthScreen;

@end
