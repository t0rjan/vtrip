//
//  emotionInput.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-24.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>

@protocol mlViewEmotionDestination <NSObject>

- (void)addEmotion:(id)sender;

@end

@interface mlViewEmotionInput : UIView

@property id <mlViewEmotionDestination> *destination;

- (void)showInView:(UIView *)view;
- (void)hide;

@end
